<?php


namespace App\Controller\Admin;

use App\Entity\Classes;
use App\Form\ClassesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class ClassesController extends AbstractController  // AbstractController c'est le contrôleur de base par symfony qui possède ses propres services, annotations etc
{
//    je crée un chemin pour ma méthode, avec le nom pour l'appeler plus tard
    /**
     * @Route("/admin/classes", name="display_classes")
     */
    //creation de méthode si j'ai besoin je fais autowiring
    // (J'appelle Le câblage automatique fait par symfony pour gérer les services avec une configuration minimale)
    public function displayClasses(ClassesRepository $classesRepository)
    {
        // création variable où je vais stocker des données appellées par
        // le service repository dans lequel je fais select
        $classes = $classesRepository->findAll();

        // par service render je donne le résultat à mon twig et du coup mon html
        return $this->render('admin/admin_classes.html.twig',[
            'classes' => $classes,

        ]);
    }

    //je crée un chemin pour pour ma méthode, avec le nom pour l'appeler plus tard
    /**
     * @Route("/admin/classes/insert", name="admin_classes_insert")
     */
    //creation de méthode si j'ai besoin je fais autowiring
    // (J'appelle Le câblage automatique fait par symfony pour gérer les service avec une configuration minimale)
    public function adminClassesInsert(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger)
    {
        // je crée une variable (pour l'instant vide) pour stocker entity Classes
        $class = new Classes();

        // je récupère formu gabarit de formulaire class et je le relie avec ma nouvelle variable
        $formClass =$this->createForm(ClassesType::class, $class);

        // pour récupère et traite les données du formulaire : j'appelle la méthode handlerequest et après je les stock dans variable
        $formClass->handleRequest($request);
        // je vérifie si ma requête est bien passée et bien envoyée, et si les données correspondent
        if($formClass->isSubmitted() && $formClass->isValid()) {
            // recupérer
            $class = $formClass->getData();
            // je recupére l'icon de url get par getData et je le stock en iconFile
            $iconFile = $formClass->get('icon')->getData();

            // je vérifie si l'icon a été bien envoyé,
            // méthode getClientOriginal pour récupérer le nom de fichier (icon)
            // slugger vérifie la chaîne d'origine, et si il y a un symbole spécial il le change pour l'adapter automatiquement
            // service uniqid me chiffre mon fichier et donne un nom unique qui se ne répète pas dans la bdd
            if ($iconFile){
                $orgFilename= pathinfo($iconFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($orgFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$iconFile->guessExtension();

                try {
                    $iconFile->move(
                        $this->getParameter('icon_directory'),
                        $newFilename
                    );
                }catch (FileException $e){

                }
                $class->setIcon($newFilename);

            }

            // j'enregistre $entity avec les données de $class dans la bdd
            $entityManager ->persist($class);
            $entityManager ->flush();

            // addFlash service qui ajoute un message
            $this->addFlash('success', 'Votre Cours '.$class->getName().' a bien été créé');

            // quand tout est bon, je redirige la route par name
            return $this->redirectToRoute('display_classes');
        }

        return $this->render('admin/insert_update_classes.html.twig',[
            'formClassView' => $formClass->createView(),
        ]);

    }

    /**
     * @Route("/admin/update/classes/{id}", name="admin_classes_update")
     */
    public function adminClassesUpdate(
        $id,
        ClassesRepository $classesRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger
    )
    {
        $class = $classesRepository->find($id);
        $formClass =$this->createForm(ClassesType::class, $class);

        $formClass->handleRequest($request);
        if($formClass->isSubmitted() && $formClass->isValid()) {
            $class = $formClass->getData();
            $iconFile = $formClass->get('icon')->getData();

            if ($iconFile){
                $orgFilename= pathinfo($iconFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($orgFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$iconFile->guessExtension();

                try {
                    $iconFile->move(
                        $this->getParameter('icon_directory'),
                        $newFilename
                    );
                }catch (FileException $e){

                }
                $class->setIcon($newFilename);

            }

            $entityManager ->persist($class);
            $entityManager ->flush();

            $this->addFlash('success', 'Votre Cours '.$class->getName().' a bien été modifié');

            return $this->redirectToRoute('display_classes');
        }

        return $this->render('admin/insert_update_classes.html.twig',[
            'formClassView' => $formClass->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_classes_delete")
     */
    public function adminClassesDelete(
        $id,
        EntityManagerInterface $entityManager,
        ClassesRepository $classesRepository)
    {
        // je trouve ma class par id et j'utilise le service remove pour la supprimer
        $class = $classesRepository->find($id);
        $entityManager ->remove($class);
        $entityManager -> flush($class);


        $this->addFlash('success', 'Votre Cours '.$class->getName().' a bien été supprimé');
        return $this->redirectToRoute('display_classes');
    }
}