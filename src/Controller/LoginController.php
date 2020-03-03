<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/afterLogin", name="afterLogin")
     */
    public function index()
    {
        $rolesUsers= $this->get('security.token_storage')->getToken()->getUser()->getRoles();
        return self::showUsers($rolesUsers);




        /*return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);*/
    }
    /**
    * @Route("/showusers", name="showusers")
    */
    public function showUsers(array $rolesUsers){

        $rolSelected= $rolesUsers;
        $users = $this->getDoctrine()->getRepository(User::class)->getUsersByRole($rolSelected);

        if (!$users) {
            throw $this->createNotFoundException(
                'No user found'
            );
        }

        switch ($rolesUsers[0]) {
            case 'ROLE_USER':
                $users = $this->getDoctrine()->getRepository(User::class)->getUsersByRole($rolSelected);
                return $this->render('login/table_roleuser.html.twig', ['users' => $users]);
                break;
            case 'ROLE_SUPER':
                array_push($rolSelected, 'ROLE_USER');
                $users = $this->getDoctrine()->getRepository(User::class)->getUsersByRole($rolSelected);
                return $this->render('login/table.html.twig', ['users' => $users]);
                break;
            case 'ROLE_ADMIN':
                $users = $this->getDoctrine()->getRepository(User::class)->getAllRegisteredUsers();
                return $this->render('login/table.html.twig', ['users' => $users]);
                break;
        }
    }
}
