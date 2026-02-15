<?php
namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $usuario = new User();
        $form = $this->createForm(RegisterType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Verificar si existe el username
            $existingUser = $em->getRepository(User::class)
                ->findOneBy(['username' => $usuario->getUsername()]);

            if ($existingUser) {
                $this->addFlash('error', 'El nombre de usuario ya existe. Por favor elige otro.');
                return $this->redirectToRoute('app_register');
            }

            // Hashear la contraseña
            $hashedPassword = $passwordHasher->hashPassword($usuario, $usuario->getPassword());
            $usuario->setPassword($hashedPassword);

            // -----------------------------
            // Roles automáticos
            // -----------------------------
            // Opción 1: Primer usuario registrado → admin
            $userCount = $em->getRepository(User::class)->count([]);
            if ($userCount === 0) {
                $usuario->setRoles(['ROLE_ADMIN']);
            }
            // Opción 2: Si el username es "admin" → admin (solo para localhost, seguro aquí)
            elseif (strtolower($usuario->getUsername()) === 'admin') {
                $usuario->setRoles(['ROLE_ADMIN']);
            }
            else {
                $usuario->setRoles(['ROLE_USER']);
            }

            $em->persist($usuario);
            $em->flush();
            $this->addFlash('success', 'Usuario registrado correctamente');

            return $this->redirectToRoute('login');
        }

        return $this->render('register/index.html.twig', [
            'formulario' => $form->createView(),
            'error' => $error,
        ]);
    }
}
