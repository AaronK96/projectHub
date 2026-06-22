<?php

namespace App\Controller;

use App\Entity\Subtask;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'app_tasks')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks'           =>  $tasks
        ]);
    }

    #[Route('/task/{id}', name: 'task_show')]
    public function show(EntityManagerInterface $entityManager, Task $task): Response
    {
        $subtasks = $task->getSubtasks();

        return $this->render('task/show.html.twig', [
            'controller_name' => 'TaskController',
            'task'           =>  $task,
            'subtasks'       => $subtasks,
        ]);
    }
}
