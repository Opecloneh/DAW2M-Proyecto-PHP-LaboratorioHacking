<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TutorialController extends AbstractController
{
    #[Route('/tutorials/{vuln}', name: 'app_tutorial', defaults: ['vuln' => 'introduccion'])]
    public function index(string $vuln): Response
    {
        $allowedPages = [
            'introduccion',
            'sql-injection',
            'xss',
            'idor',
            'file-upload'
        ];

        if (!in_array($vuln, $allowedPages)) {
            throw $this->createNotFoundException();
        }

        return $this->render('tutorial/index.html.twig', [
            'currentVuln' => $vuln
        ]);
    }
}
