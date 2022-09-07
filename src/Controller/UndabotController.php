<?php

namespace App\Controller;

use App\Manager\UndabotManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
     * @Route("/undabot", name="app_undabot")
     */
class UndabotController extends AbstractController {

    /**
     * @Route("/index", name="project_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return $this->json( [
            'controller_name' => 'UndabotController',
        ]);
    }

    /**
     * @Route("/getTermScore", name="project_getTermScore", methods={"POST"})     *
     * @return JsonResponse
     */
    public function getTermScore(Request $request, EntityManagerInterface $entityManager): JsonResponse {
        $undabotManager = new UndabotManager($entityManager);
        return $this->json($undabotManager->getTermScore($request));
    }
}
