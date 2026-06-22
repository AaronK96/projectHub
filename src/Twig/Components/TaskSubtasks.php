<?php

namespace App\Twig\Components;

use App\Entity\Subtask;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TaskSubtasks
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public Task $task;

    #[LiveProp]
    public string $variant = 'full';

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

        $this->emit('subtaskUpdated', [
            'taskId' => $this->task->getId()
        ]);
    }

    #[LiveListener('subtaskUpdated')]
    public function refreshAfterSubtaskUpdate(#[LiveArg] int $taskId): void {
        if($taskId !== $this->task->getId()) {
            return;
        }

        $this->entityManager->refresh($this->task);
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

    public function getVisibleSubtasks(): iterable 
    {
        if($this->variant === 'compact') {
            return $this->task->getSubtasks()->slice(0, 4);
        }

        return $this->task->getSubtasks();
    }
}
