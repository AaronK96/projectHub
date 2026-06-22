<?php

namespace App\Twig\Components;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TaskQuickMetaEditor
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public Task $task;

    #[LiveProp(writable: true)]
    public string $selectedStatus = '';

    #[LiveProp(writable: true)]
    public string $selectedPriority = '';

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        //
    }

    public function mount(Task $task): void 
    {
        $this->task = $task;
        $this->selectedStatus = $task->getStatus();
        $this->selectedPriority = $task->getPriority();
    }

    #[LiveAction]
    public function updateStatus(): void 
    {
        if(!in_array($this->selectedStatus, Task::availableStatuses(), true)) {
            return;
        }

        $this->task->setStatus($this->selectedStatus);
        
        $this->entityManager->flush();

        $this->emit('taskUpdated', [
            'taskId' => $this->task->getId()
        ]);
    }

    #[LiveAction]
    public function updatePriority(): void 
    {
        if(!in_array($this->selectedPriority, Task::availablePriorities(), true)) {
            return;
        }    

        $this->task->setPriority($this->selectedPriority);

        $this->entityManager->flush();

        $this->emit('taskUpdated', [
            'taskId' => $this->task->getId()
        ]);
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            Task::STATUS_TODO => 'To Do',
            Task::STATUS_IN_PROGRESS => 'In Progress',
            Task::STATUS_REVIEW => 'Review',
            Task::STATUS_DONE => 'Done',
            default => $status,
        };
    }

    public function getPriorityLabel(string $priority): string
    {
        return match ($priority) {
            Task::PRIORITY_LOW => 'Niedrig',
            Task::PRIORITY_MEDIUM => 'Mittel',
            Task::PRIORITY_HIGH => 'Hoch',
            Task::PRIORITY_URGENT => 'Dringend',
            default => $priority,
        };
    }


}
