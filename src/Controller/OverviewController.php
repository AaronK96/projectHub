<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OverviewController extends AbstractController
{
    #[Route('/overview', name: 'app_overview')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        //$openTasks = $entityManager->getRepository(Task::class)->findBy([
        //    'status' => ['active', 'review']
        //]);

        $taskCount = [];

        $taskActiveCount = 0;
        $taskReviewCount = 0;
        $taskDoneCount = 0;

        foreach ($tasks as $task) {
            switch ($task->getStatus()) {
                case 'active':
                    $taskActiveCount++;
                    break;
                case 'review':
                    $taskReviewCount++;
                    break;
                case 'done':
                    $taskDoneCount++;
                    break;
            }
        }

        $taskCount['active'] = $taskActiveCount;
        $taskCount['review'] = $taskReviewCount;
        $taskCount['done'] = $taskDoneCount;
        $taskCount['open'] = $taskActiveCount + $taskReviewCount;

        dump($taskCount);

        return $this->render('overview/index.html.twig', [
            'controller_name' => 'OverviewController',
            'projects' => $projects,
            'taskCount' => $taskCount,
        ]);
    }
}
