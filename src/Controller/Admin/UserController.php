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
class UserController extends AbstractController
{
    /**
     * @Route("/", name="page_admin")
     */
    public function adminPage()
    {
      return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/user", name="role")
     */
    //function d'affichage de mes usagés
    public function roleUser(UserRepository $repository)
    {
        $users = $repository->findAll();

        return $this->render('admin/role.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/update", name="roleUpdate")
     */
    public function roleUpdate(
        UserRepository $repository,
        Request $request,
        EntityManagerInterface $entityManager)
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

        $this->addFlash('success','Le User '.$user->getFirstname().' '.$user->getName() .' était bien modifié!');
        return $this->redirectToRoute('role');
    }

    /**
     * @Route("/delete/user/{id}", name="userDelete")
     */
    public function userDelete(
        $id,
        EntityManagerInterface $entityManager,
        UserRepository $repository)
    {
        $user = $repository->find($id);
        $entityManager ->remove($user);
        $entityManager ->flush($user);

        $this->addFlash('success','Le User '.$user->getFirstname().' '.$user->getName() .' était bien supprimé!');
        return $this->redirectToRoute('role');
    }




}

