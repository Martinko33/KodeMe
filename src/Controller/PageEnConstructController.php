<?php


    namespace App\Controller;


    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class PageEnConstructController extends AbstractController
    {
        /**
         * @Route("/construction", name="construct_page")
         */
        public function ConstructPage(){
            return $this->render('construction.html.twig');
        }
    }