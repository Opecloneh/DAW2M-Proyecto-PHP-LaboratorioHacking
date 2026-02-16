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
        $lab = $labRepo->find($id); // Buscar laboratorio por ID
        if (!$lab) {
            throw $this->createNotFoundException('Laboratorio no encontrado'); // Si no existe, lanzar 404
        }

        $user = $this->getUser(); // Obtener usuario logueado
        $completed = false; // Inicializar como no completado

        if ($user) {
            $progress = $progressRepo->findOneBy(['user' => $user, 'laboratory' => $lab]); // Buscar progreso del usuario
            $completed = $progress ? $progress->isCompleted() : false; // Verificar si ya completó
        }

        return $this->render('challenges/show.html.twig', [
            'lab' => $lab, // Pasar laboratorio a la vista
            'completed' => $completed, // Pasar estado de completado
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
        $lab = $labRepo->find($id); // Buscar laboratorio por ID
        $user = $this->getUser(); // Obtener usuario logueado

        if (!$lab || !$user) {
            throw $this->createNotFoundException(); // Si no existe laboratorio o usuario, 404
        }

        $payload = $request->request->get('payload'); // Obtener respuesta enviada por formulario
        $isCorrect = trim(strtolower($payload)) === trim(strtolower($lab->getSolution())); // Comparar con solución

        $progress = $progressRepo->findOneBy(['user' => $user, 'laboratory' => $lab]) ?? new Progress(); // Buscar progreso o crear uno nuevo
        if (!$progress->getId()) {
            $progress->setUser($user)->setLaboratory($lab); // Asignar usuario y laboratorio si es nuevo
        }

        $progress->setCompleted($isCorrect); // Marcar como completado si es correcto
        $em->persist($progress); // Preparar para guardar
        $em->flush(); // Guardar en la base de datos

        return $this->render('challenges/show.html.twig', [
            'lab' => $lab, // Pasar laboratorio a la vista
            'completed' => $isCorrect, // Indicar si está completado
            $isCorrect ? 'success' : 'error' => $isCorrect ? '¡Correcto! Has completado el laboratorio 💜' : 'Respuesta incorrecta, inténtalo de nuevo' // Mensaje dinámico según resultado
        ]);
    }
}
