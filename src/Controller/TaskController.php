<?php

namespace App\Controller;

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
}
