<?php

namespace App\Twig\Components;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TaskSidebar
{
    use DefaultActionTrait;

    #[LiveProp]
    public Task $task;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        //
    }

    #[LiveListener('taskUpdated')]
    public function refreshAfterTaskUpdate(#[LiveArg] int $taskId): void {
        if($taskId !== $this->task->getId()) {
            return;
        }

        $this->entityManager->refresh($this->task);
    }
}
