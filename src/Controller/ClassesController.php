<?php


namespace App\Controller;


use App\Repository\ClassesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClassesController extends AbstractController
{
    /**
     * @Route("/cours", name="page_cours")
     */
    public function coursPage(ClassesRepository $classesRepository)
    {
        $classes = $classesRepository->findAll();
        return $this->render('classes.html.twig',[
            'classes' => $classes
        ]);
    }

//    /**
//     * @Route("/cours/gratuit", name="page_cours_gratuit")
//     */
//    public function coursGratuit(ClassesRepository $classesRepository
//    {
//        $classes = $classesRepository->findBy()
//    })
}