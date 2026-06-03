<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/projects', name: 'app_projects')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projects
        ]);
    }
}
