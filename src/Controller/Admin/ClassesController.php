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

class ClassesController extends AbstractController  // AbstractController c'est la controleur de base pas symfony avec ses service, annotation etc
{
//    je créé chemin pour pour ma méthode, avec nom pour l'appelé plus tard
    /**
     * @Route("/admin/classes", name="display_classes")
     */
    //creation de méthode si j'ai besoin je fais autowiring
    // ( Le câblage automatique fait par symfony pour gere les service avec configuration minimal quelle j'appelle)
    public function displayClasses(ClassesRepository $classesRepository)
    {
        // création variable ou je vais stocker donné appellé par
        // service repository où je fais select
        $classes = $classesRepository->findAll();

        // par service render je rendre résultat a mon twig et du coup mon html
        return $this->render('admin/admin_classes.html.twig',[
            'classes' => $classes,

        ]);
    }

    //je créé chemin pour pour ma méthode, avec nom pour l'appelé plus tard
    /**
     * @Route("/admin/classes/insert", name="admin_classes_insert")
     */
    //creation de méthode si j'ai besoin je fais autowiring
    // ( Le câblage automatique fait par symfony pour gere les service avec configuration minimal quelle j'appelle)
    public function adminClassesInsert(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger)
    {
        // je cree variable (pour instant vide) pour stocke entity Classes
        $class = new Classes();

        // je récouper formu gabarit de formulaire class et je le rélie avec ma nouvelle variable
        $formClass =$this->createForm(ClassesType::class, $class);

        // pour récoupere et traiter les donnés du formulaire j'appelle méthode handlerequest et aprés je les stock dans variable
        $formClass->handleRequest($request);
        // je verifie si mon requete est bien passé bien envoyé et si les données corresponde
        if($formClass->isSubmitted() && $formClass->isValid()) {
            // recupérer
            $class = $formClass->getData();
            // je recouper icon de url get par getData et je le stock en iconFile
            $iconFile = $formClass->get('icon')->getData();

            // je vérifie si icon été bien envoyé,
            // méthode getClientOriginal récouper le nom de fichier (icon)
            // slugger vérifi la chaîne origine et si il ya symbol special il les addapte automatiquement
            // service uniqid me chiffre mon fichier et don nom unique que se ne répete pas dans la bdd
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

            // je enregistre persiste $entity avec donne $class dans la bdd
            $entityManager ->persist($class);
            $entityManager ->flush();

            // addFlash service qui ajoute un message
            $this->addFlash('success', 'Votre Cour '.$class->getName().' étais bien crée');

            // quand c'est bon je redirect la route par name
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

            $this->addFlash('success', 'Votre Cour '.$class->getName().' etais bien modifie');

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
        // je trouve ma class par id et je utilise service remove pour la supprimé
        $class = $classesRepository->find($id);
        $entityManager ->remove($class);
        $entityManager -> flush($class);


        $this->addFlash('success', 'Votre Cour '.$class->getName().' étais bien supprimé');
        return $this->redirectToRoute('display_classes');
    }
}