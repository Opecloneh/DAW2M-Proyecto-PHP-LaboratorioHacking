<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LaboratoryRepository;
use App\Repository\ProgressRepository;

class ChallengesController extends AbstractController
{
    #[Route('/challenges', name: 'app_challenges')]
    public function index(
        LaboratoryRepository $labRepo,
        ProgressRepository $progressRepo
    ): Response
    {
        $user = $this->getUser();
        $labs = $labRepo->findAll();
        $completedLabs = [];

        if ($user) {
            $progress = $progressRepo->findBy(['user' => $user]);
            foreach ($progress as $p) {
                if ($p->isCompleted()) {
                    $completedLabs[$p->getLaboratory()->getId()] = true;
                }
            }
        }

        return $this->render('challenges/index.html.twig', [
            'labs' => $labs,
            'completedLabs' => $completedLabs,
        ]);
    }
}
