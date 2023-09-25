<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'APP_LOGIN')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/admin4983ad/login', name: 'ADMIN_LOGIN')]
    public function adminLogin(AuthenticationUtils $authenticationUtils): Response
    {
        $error        = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            // parameters usually defined in Symfony login forms
            'error'                => $error,
            'last_username'        => $lastUsername,

            'page_title'           => 'Connexion a l\'espace d\'administration',
            'csrf_token_intention' => 'authenticate',
            'target_path'          => $this->generateUrl('ADMIN_DASHBOARD'),
            'username_label'       => 'Identifiant',
            'password_label'       => 'Mot de passe',
            'sign_in_label'        => 'Se connecter',
            'username_parameter'   => 'email',
            'password_parameter'   => 'password',
            'remember_me_enabled'  => true,
            'remember_me_checked'  => true,
            'remember_me_label'    => 'Remember me',
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
