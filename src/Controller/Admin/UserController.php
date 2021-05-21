<?php


namespace App\Controller\Admin;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
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
    //fonction d'affichage de mes usagés
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
        // requête pour trouver l'usagé concerné par la modification grâce a l'id
        $user = $repository->findOneBy(['id'=>$request->request->get('id')]);

        //condition de changement du role
        if ($request->request->get('role') === "admin"){
            $role = array('ROLE_ADMIN');
        } elseif($request->request->get('role') === "user"){
            $role = array('ROLE_USER');
        } elseif ($request->request->get('role') === "teacher"){
            $role = array('ROLE_TEACHER');
        }

        //envoie du nouveau rôle
        $user->setRoles($role);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success','L utilisateur '.$user->getFirstname().' '.$user->getName() .'a bien été modifié!');
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

        $this->addFlash('success','L utilisateur '.$user->getFirstname().' '.$user->getName() .' a bien été supprimé!');
        return $this->redirectToRoute('role');
    }




}

