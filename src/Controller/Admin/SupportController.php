<?php


namespace App\Controller\Admin;

use App\Entity\Support;
use App\Form\SupportType;
use App\Repository\ClassesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SupportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class SupportController extends AbstractController
{

    /**
     * @Route("/admin/support", name="display_support")
     */
    public function displaySupports( ClassesRepository $classesRepository, SupportRepository $supportRepository)
    {
        $supports = $supportRepository->findAll();
        $classes = $classesRepository->findAll();
        return $this->render('admin/admin_support.html.twig',[
          'supports' => $supports,
            'classes' => $classes
        ]);

    }

    /**
     * @Route("/admin/support/insert", name="admin_support_insert")
     */
    public function adminSupportInsert(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger)
    {
        $support = new Support();

        $formSupport =$this->createForm(SupportType::class, $support);

        $formSupport->handleRequest($request);

        if($formSupport->isSubmitted() && $formSupport->isValid()) {
            $support = $formSupport->getData();

            $imageFile = $formSupport->get('image')->getData();
            if ($imageFile){
                $orgFilename= pathinfo($imageFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($orgFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                }catch (FileException $e){

                }
                $support->setImage($newFilename);
            }

            $videoFile = $formSupport->get('video')->getData();
            if ($videoFile){

                $orgVideoname= pathinfo($videoFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeVideoname = $slugger->slug($orgVideoname);
                $newVideoname = $safeVideoname.'-'.uniqid().'.'.$videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('video_directory'),
                        $newVideoname
                    );
                }catch (FileException $e){

                }
                $support->setVideo($newVideoname);

            }

            $entityManager ->persist($support);

            $entityManager ->flush();

            $this->addFlash('success', 'Votre support '.$support->getTitre().' de cours a bien été créé');

            return $this->redirectToRoute('display_support');
        }

        return $this->render('admin/insert_update_support.html.twig',[
            'formSupportView' => $formSupport->createView(),
        ]);

    }

    /**
     * @Route("/admin/update/support/{id}", name="admin_support_update")
     */
    public function adminSupportUpdate(
        $id,
        SupportRepository $supportRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger
    )
    {
        $support = $supportRepository->find($id);
        $formSupport =$this->createForm(SupportType::class, $support);

        $formSupport->handleRequest($request);
        if($formSupport->isSubmitted() && $formSupport->isValid()) {
            $support = $formSupport->getData();

            $imageFile = $formSupport->get('image')->getData();
            if ($imageFile){
                $orgFilename= pathinfo($imageFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($orgFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                }catch (FileException $e){

                }
                $support->setImage($newFilename);

            }

            $videoFile = $formSupport->get('video')->getData();
            if ($videoFile){

                $orgVideoname= pathinfo($videoFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeVideoname = $slugger->slug($orgVideoname);
                $newVideoname = $safeVideoname.'-'.uniqid().'.'.$videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('video_directory'),
                        $newVideoname
                    );
                }catch (FileException $e){

                }
                $support->setVideo($newVideoname);

            }

            $entityManager ->persist($support);
            $entityManager ->flush();

            $this->addFlash('success', 'Votre support '.$support->getTitre().' de cours a bien été modifié');

            return $this->redirectToRoute('display_support');
        }

        return $this->render('admin/insert_update_support.html.twig',[
            'formSupportView' => $formSupport->createView(),
            'support'
        ]);
    }

    /**
     * @Route("/admin/delete/support/{id}", name="admin_support_delete")
     */
    public function adminClassesDelete(
        $id,
        EntityManagerInterface $entityManager,
        SupportRepository $supportRepository)
    {
        $support = $supportRepository->find($id);
        $entityManager ->remove($support);
        $entityManager -> flush($support);


        $this->addFlash('success', 'Votre support '.$support->getTitre().' de cours a bien été supprimé');
        return $this->redirectToRoute('display_support');
    }


}