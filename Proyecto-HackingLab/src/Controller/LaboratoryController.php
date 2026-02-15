<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LaboratoryRepository;
use App\Repository\ProgressRepository;
use App\Entity\Progress;
use Doctrine\ORM\EntityManagerInterface;

class LaboratoryController extends AbstractController
{
    #[Route('/challenges/{id}', name: 'laboratory_show')]
    public function show(
        int $id,
        LaboratoryRepository $labRepo,
        ProgressRepository $progressRepo
    ): Response
    {
        $lab = $labRepo->find($id);
        if (!$lab) {
            throw $this->createNotFoundException('Laboratorio no encontrado');
        }

        $user = $this->getUser();
        $completed = false;

        if ($user) {
            $progress = $progressRepo->findOneBy(['user' => $user, 'laboratory' => $lab]);
            $completed = $progress ? $progress->isCompleted() : false;
        }

        return $this->render('challenges/show.html.twig', [
            'lab' => $lab,
            'completed' => $completed,
        ]);
    }

    #[Route('/challenges/{id}/submit', name: 'laboratory_submit', methods: ['POST'])]
    public function submit(
        int $id,
        Request $request,
        LaboratoryRepository $labRepo,
        ProgressRepository $progressRepo,
        EntityManagerInterface $em
    ): Response
    {
        $lab = $labRepo->find($id);
        $user = $this->getUser();

        if (!$lab || !$user) {
            throw $this->createNotFoundException();
        }

        $payload = $request->request->get('payload');
        $isCorrect = trim(strtolower($payload)) === trim(strtolower($lab->getSolution()));

        $progress = $progressRepo->findOneBy(['user' => $user, 'laboratory' => $lab]) ?? new Progress();
        if (!$progress->getId()) {
            $progress->setUser($user)->setLaboratory($lab);
        }

        $progress->setCompleted($isCorrect);
        $em->persist($progress);
        $em->flush();

        return $this->render('challenges/show.html.twig', [
            'lab' => $lab,
            'completed' => $isCorrect,
            $isCorrect ? 'success' : 'error' => $isCorrect ? '¡Correcto! Has completado el laboratorio 💜' : 'Respuesta incorrecta, inténtalo de nuevo'
        ]);
    }
}
