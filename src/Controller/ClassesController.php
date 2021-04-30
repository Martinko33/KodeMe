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
     *
     * @Route("/cours/{name}", name="page_cour")
     */
    public function pageCour($name,
        ClassesRepository $classesRepository,
        SupportRepository $supportRepository)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Si vous voulez rentrer dans le cour faut s'enregistrer");
        $class = $classesRepository ->findBy(["name" =>$name]);

//        $supports = $supportRepository ->findAll();

        return $this->render('class.html.twig',[
            "class" => $class,
//            "supports" => $supports,
        ]);
    }

    /**
     * @Route("/search", name="search_classes")
     */
    public function searchClasses(Request $request, ClassesRepository $classesRepository)
    {
        // je fais requete query quelle me recoupere dans mon input recherche le mot que utilisateur ecris
        // je utilise la methode get pour recoupere ca de URL
        $search = $request->query->get('search');
        // je recouper dans ma variabla valeur de $search qui a passe par la function searchByTerm
        // quelle se trouve dans Repository-> classesrepository
        $classes = $classesRepository->searchByTerm($search);

        // je envoie ma class vers html.twig avec ma variable $classes
        return $this->render('search.html.twig', [
            'classes' => $classes
        ]);



    }
}