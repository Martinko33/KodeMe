<?php


namespace App\Controller\Admin;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class adminController extends AbstractController
{
    /**
     * @Route("/", name="adminHome")
     */
    public function adminHome()
    {

    }

    /**
     * @Route("/role", name="role")
     */
    //function d'affichage de mes usagés
    public function role(UserRepository $repository)
    {
        $users = $repository->findAll();

        return $this->render('admin/role.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/roleUpdate", name="roleUpdate")
     */
    public function roleUpdate( UserRepository $repository, Request $request, EntityManagerInterface $entityManager)
    {
        // requete pour trouver l'usagé concerné par la modification grace a l'id
        $user = $repository->findOneBy(['id'=>$request->request->get('id')]);

        //condition de changement du role
        if ($request->request->get('role') === "admin"){
            $role = array('ROLE_ADMIN');
        } elseif($request->request->get('role') === "user"){
            $role = array('ROLE_USER');
        } elseif ($request->request->get('role') === "teacher"){
            $role = array('ROLE_TEACHER');
        }

        //envoi du nouveau role
        $user->setRoles($role);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('role');
    }
}
