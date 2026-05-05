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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        // Si l'utilisateur est déjà connecté, rediriger vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_subscription_index');
        }

        if ($request->get('email')) {
            // Chercher l'utilisateur par EMAIL
            $user = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(['email' => $request->get('email')]);

            if ($user) {
                // 1 - Enregistrer la demande de reset dans la base
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTimeImmutable());
                
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                // 2 - Générer le lien de réinitialisation (URL absolue)
                $url = $this->generateUrl('update_password', [
                    'token' => $resetPassword->getToken()
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                // 3 - Contenu de l'email
                $content = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #667eea;'>Bonjour " . htmlspecialchars($user->getUsername()) . ",</h2>
                        <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
                        <p>Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . $url . "' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; display: inline-block;'>Réinitialiser mon mot de passe</a>
                        </div>
                        <p style='font-size: 12px; color: #999;'>Ce lien expirera dans 30 minutes.</p>
                        <hr>
                        <p style='font-size: 12px; color: #999;'>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
                    </div>
                ";

                // 4 - Envoyer l'email via Mailjet
                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getUsername(), 'Réinitialisation de votre mot de passe', $content);
                
                $this->addFlash('notice', 'Un email vous a été envoyé avec un lien de réinitialisation.');
            } else {
                $this->addFlash('notice', 'Aucun compte n\'est associé à cet email.');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/update-password/{token}', name: 'update_password')]
    public function reset($token, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        // Vérifier si le token existe
        $resetPassword = $this->entityManager
            ->getRepository(ResetPassword::class)
            ->findOneBy(['token' => $token]);

        if (!$resetPassword) {
            $this->addFlash('notice', 'Token invalide. Veuillez recommencer.');
            return $this->redirectToRoute('reset_password');
        }

        // Vérifier si le token n'a pas expiré (30 minutes)
        $now = new \DateTime();
        $expirationDate = $resetPassword->getCreatedAt()->modify('+30 minutes');
        
        if ($expirationDate < $now) {
            // Supprimer le token expiré
            $this->entityManager->remove($resetPassword);
            $this->entityManager->flush();
            
            $this->addFlash('notice', 'Votre demande a expiré. Veuillez recommencer.');
            return $this->redirectToRoute('reset_password');
        }

        // Créer le formulaire de nouveau mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            
            // Mettre à jour le mot de passe
            $user = $resetPassword->getUser();
            $user->setPassword($encoder->hashPassword($user, $newPassword));
            
            // Supprimer le token utilisé
            $this->entityManager->remove($resetPassword);
            $this->entityManager->flush();
            
            $this->addFlash('notice', 'Votre mot de passe a été mis à jour avec succès !');
            return $this->redirectToRoute('login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'form' => $form->createView()
        ]);
    }
}