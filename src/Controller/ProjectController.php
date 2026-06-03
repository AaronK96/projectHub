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

        $progress = [];
        foreach ($projects as $project) {
            $doneCount = 0;
            $taskCount = count($project->getTasks());
            
            foreach ($project->getTasks() as $task) {
                if($task->getStatus() == 'done') {
                    $doneCount++;
                }
                
            }
            
            $progress[$project->getId()] = round(($doneCount / $taskCount) * 100);
        }

        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projects,
            'progress' => $progress
        ]);
    }
}
