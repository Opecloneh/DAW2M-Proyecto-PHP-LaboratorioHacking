<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\FormError;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/users', name: 'admin_')] // Prefijo de URL y de nombre de ruta
class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    // -----------------------------
    // LISTAR USUARIOS
    // -----------------------------
    #[Route('/', name: 'users', methods: ['GET'])] // Nombre final: admin_users
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    // -----------------------------
    // CREAR USUARIO
    // -----------------------------
    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])] // admin_user_new
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        // Evitar crear otro "admin" desde el formulario
        if ($user->getUsername() && strtolower($user->getUsername()) === 'admin') {
            $form->get('username')->addError(new FormError('No se puede crear otro usuario admin.'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Roles automáticos: si username = admin → ROLE_ADMIN
            if (strtolower($user->getUsername()) === 'admin') {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Usuario creado correctamente.');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // -----------------------------
    // EDITAR USUARIO
    // -----------------------------
    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        // Solo deshabilitar el campo username si el usuario es el admin
        $isAdmin = strtolower($user->getUsername()) === 'admin';

        $form = $this->createForm(AdminType::class, $user, [
            'disable_username' => $isAdmin,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si cambió la contraseña
            if ($user->getPassword()) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            }

            // Roles automáticos
            if ($isAdmin) {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Usuario actualizado correctamente.');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    // -----------------------------
    // BORRAR USUARIO
    // -----------------------------
    #[Route('/{id}/delete', name: 'user_delete', methods: ['POST'])] // admin_user_delete
    public function delete(Request $request, User $user): Response
    {
        // Nunca borrar al admin
        if (strtolower($user->getUsername()) === 'admin') {
            $this->addFlash('warning', 'El usuario admin no puede ser borrado.');
            return $this->redirectToRoute('admin_users');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Usuario borrado correctamente.');
        }

        return $this->redirectToRoute('admin_users');
    }
}
