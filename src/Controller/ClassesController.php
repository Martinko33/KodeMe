<?php


namespace App\Controller;


use App\Repository\ClassesRepository;
use App\Repository\SupportRepository;
use App\Repository\ThemeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $themes = $themeRepository->findAll();
        $theme = $themeRepository -> find($id);
            return $this->render('classes.html.twig',[
                "classes" => $classes,
                "themes" => $themes,
                "theme" => $theme
            ]);
    }

    /**
     *
     * @Route("/cours/{name}", name="page_cour")
     * @IsGranted("ROLE_USER")
     */
    public function pageCour($name,
        ClassesRepository $classesRepository,
        SupportRepository $supportRepository)
    {
//        je bloque entree
        $this->denyAccessUnlessGranted('ROLE_USER', null, "Si vous voulez rentrer dans le cour faut s'enregistrer");

        $class = $classesRepository ->findBy(["name" =>$name]);
        $supports = $supportRepository ->findAll();

        return $this->render('class.html.twig',[
            "class" => $class,
//          "supports" => $supports,
        ]);

    }

    /**
     * @Route("/search", name="search_classes")
     */
    public function searchClasses(Request $request, ClassesRepository $classesRepository, ThemeRepository $themeRepository)
    {
        // je fais requete query quelle me recoupere dans mon input recherche le mot que utilisateur ecris
        // je utilise la methode get pour recoupere ca de URL
        $search = $request->query->get('search');
        // je recouper dans ma variabla valeur de $search qui a passe par la function searchByTerm
        // quelle se trouve dans Repository-> classesrepository
        $classes = $classesRepository->searchByTerm($search);

        $themes = $themeRepository->findAll();

        // je envoie ma class vers html.twig avec ma variable $classes
        return $this->render('search.html.twig', [
            'classes' => $classes,
            'themes' => $themes
        ]);



    }
}