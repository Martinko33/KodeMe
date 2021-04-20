<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="page_home")
     */
    public function homePage(ClassesRepository $classesRepository)
    {
        $classes = $classesRepository->findAll();
        return $this->render('home.html.twig',[
            'classes' => $classes
        ]);
    }
}