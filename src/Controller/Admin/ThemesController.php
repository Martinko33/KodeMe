<?php


namespace App\Controller\Admin;


use App\Entity\Theme;
use App\Form\ThemesType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ThemesController extends AbstractController
{
    /**
     * @Route("/admin/themes", name="display_themes")
     */
    public function displayClasses(ThemeRepository $themeRepository)
    {
        $themes = $themeRepository->findAll();

        return $this->render('admin/admin_themes.html.twig',[
            'themes' => $themes,

        ]);
    }

    /**
     * @Route("/admin/themes/insert", name="admin_themes_insert")
     */
    public function adminThemesInsert(
        Request $request,
        EntityManagerInterface $entityManager)
    {
        $theme = new Theme();

        $formTheme =$this->createForm(ThemesType::class, $theme);

        $formTheme->handleRequest($request);
        if($formTheme->isSubmitted() && $formTheme->isValid()) {
            $theme = $formTheme->getData();

            $entityManager ->persist($theme);
            $entityManager ->flush();

            $this->addFlash('success', 'Votre thème '.$theme->getName().' a bien été créé');

            return $this->redirectToRoute('display_themes');
        }

        return $this->render('admin/insert_update_themes.html.twig',[
            'formThemeView' => $formTheme->createView(),
        ]);

    }


    /**
     * @Route("/admin/update/themes/{id}", name="admin_themes_update")
     */
    public function adminThemesUpdate(
        $id,
        ThemeRepository $themeRepository,
        EntityManagerInterface $entityManager,
        Request $request)
    {
        $theme = $themeRepository->find($id);
        $formTheme =$this->createForm(ThemesType::class,$theme);

        $formTheme->handleRequest($request);
        if($formTheme->isSubmitted() && $formTheme->isValid()) {
            $theme = $formTheme->getData();

            $entityManager ->persist($theme);
            $entityManager ->flush();

            $this->addFlash('success', 'Votre thème '.$theme->getName().' a bien été modifié');

            return $this->redirectToRoute('display_themes');
        }

        return $this->render('admin/insert_update_themes.html.twig',[
            'formThemeView' => $formTheme->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/themes/{id}", name="admin_themes_delete")
     */
    public function adminThemesDelete(
        $id,
        EntityManagerInterface $entityManager,
        ThemeRepository $themeRepository)
    {
        $theme = $themeRepository->find($id);
        $entityManager ->remove($theme);
        $entityManager -> flush($theme);


        $this->addFlash('success', 'Votre thème '.$theme->getName().' a bien été supprimé');
        return $this->redirectToRoute('display_themes');
    }

}