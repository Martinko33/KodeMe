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

class ClassesController extends AbstractController
{
    /**
     * @Route("/admin/classes", name="display_classes")
     */
    public function displayClasses(ClassesRepository $classesRepository)
    {
        $classes = $classesRepository->findAll();

        return $this->render('admin/admin_classes.html.twig',[
            'classes' => $classes,

        ]);
    }

    /**
     * @Route("/admin/classes/insert", name="admin_classes_insert")
     */
    public function adminClassesInsert(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger)
    {
        $class = new Classes();

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

            $this->addFlash('success', 'Votre Cour '.$class->getName().' etais bien cree');

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
        $class = $classesRepository->find($id);
        $entityManager ->remove($class);
        $entityManager -> flush($class);


        $this->addFlash('success', 'Votre Cour '.$class->getName().' etais bien supprime');
        return $this->redirectToRoute('display_classes');
    }
}