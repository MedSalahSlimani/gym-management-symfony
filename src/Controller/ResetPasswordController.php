<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/forgot-password', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_subscription_index');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $request->get('email')]);

            if ($user) {
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                $url = $this->generateUrl('update_password', ['token' => $resetPassword->getToken()]);
                $content = "Bonjour " . $user->getUsername() . "<br>Cliquez sur le lien : <a href='" . $url . "'>Réinitialiser</a>";
                $mail = new Mail();
                $mail->send($user->getUsername(), $user->getUsername(), 'Réinitialisation', $content);
                $this->addFlash('notice', 'Email envoyé !');
            } else {
                $this->addFlash('notice', 'Email non trouvé');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/update-password/{token}', name: 'update_password')]
    public function reset($token, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        if (!$resetPassword) {
            return $this->redirectToRoute('reset_password');
        }

        $now = new \DateTime();
        if ($resetPassword->getCreatedAt()->modify('+30 minutes') < $now) {
            $this->addFlash('notice', 'Token expiré');
            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $resetPassword->getUser();
            $user->setPassword($encoder->hashPassword($user, $form->get('password')->getData()));
            $this->entityManager->flush();
            $this->addFlash('notice', 'Mot de passe mis à jour');
            return $this->redirectToRoute('login');
        }

        return $this->render('reset_password/reset.html.twig', ['form' => $form->createView()]);
    }
}