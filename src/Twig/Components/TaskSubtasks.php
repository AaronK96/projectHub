<?php

namespace App\Twig\Components;

use App\Entity\Subtask;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TaskSubtasks
{
    use DefaultActionTrait;

    #[LiveProp]
    public Task $task;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[LiveAction]
    public function toggleSubtask(#[LiveArg] int $subtaskId): void
    {
        $subTask = $this->entityManager
            ->getRepository(Subtask::class)
            ->find($subtaskId);

        if(!$subTask instanceof Subtask) {
            return;
        }

        if($subTask->getTask()?->getId() !== $this->task->getId()) {
            return;
        }

        $subTask->toggleCompleted();

        $this->entityManager->flush();
    }

    public function getCompletedCount(): int
    {
        return $this->task
            ->getSubtasks()
            ->filter(fn (Subtask $subTask) => $subTask->isCompleted())
            ->count();
    }

    public function getTotalCount(): int
    {
        return $this->task->getSubtasks()->count();
    }

    public function getProgress(): int
    {
        if ($this->getTotalCount() === 0) {
            return 0;
        }

        return (int) round(($this->getCompletedCount() / $this->getTotalCount()) * 100);
    }
}
