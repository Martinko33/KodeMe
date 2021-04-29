<?php


namespace App\Controller;


use App\Repository\ClassesRepository;
use App\Repository\SupportRepository;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassesController extends AbstractController
{
    /**
     * @Route("/cours", name="page_cours")
     */
    public function coursPage(ClassesRepository $classesRepository,
                              ThemeRepository $themeRepository)
    {
        $classes = $classesRepository->findAll();
        $themes = $themeRepository->findAll();
        return $this->render('classes.html.twig',[
            'classes' => $classes,
            'themes' => $themes
        ]);
    }

    /**
     * @Route("/cours/theme/{id}", name="page_cours_select")
     */
    public function coursSelect(
        ClassesRepository $classesRepository,
        $id,
        ThemeRepository $themeRepository)
    {
        $classes = $classesRepository->findBy(['themes'=>$id]);
        $theme = $themeRepository->findAll();
        $themes = $themeRepository -> find($id);
            return $this->render('classes.html.twig',[
                "classes" => $classes,
                "themes" => $themes,
                "theme" => $theme
            ]);
    }

    /**
     * @Route("/cours/{name}", name="page_cour")
     */
    public function pageCour($name,
        ClassesRepository $classesRepository,
        SupportRepository $supportRepository)
    {
        $class = $classesRepository ->findBy(["name" =>$name]);

//        $supports = $supportRepository ->findAll();

        return $this->render('class.html.twig',[
            "class" => $class,
//            "supports" => $supports,
        ]);
    }
}