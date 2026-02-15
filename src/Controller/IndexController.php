<?php

namespace App\Controller;

use App\Repository\LaboratoryRepository;
use App\Repository\ProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(LaboratoryRepository $labRepository, ProgressRepository $progressRepository): Response
    {


        // Bloquea acceso a usuarios no autenticados
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Obtiene el usuario actual
        $usuario = $this->getUser();

        $totalChallenges = $labRepository->count([]);
        $completedCount = $progressRepository->count([
            'user' => $usuario,
            'completed' => true,
        ]);

        return $this->render('index/index.html.twig', [
            'username' => $usuario->getUsername(),
            'totalChallenges' => $totalChallenges,
            'completedCount' => $completedCount,
        ]);
}

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony Security maneja el logout automáticamente
        throw new \LogicException('Este método puede estar vacío, será interceptado por el firewall.');
    }
}
