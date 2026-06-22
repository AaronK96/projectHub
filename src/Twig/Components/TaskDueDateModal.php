<?php

namespace App\Twig\Components;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TaskDueDateModal
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public Task $task;

    #[LiveProp(writable: true)]
    public ?string $selectedDueDate = null;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        //
    }

    public function mount(Task $task): void
    {
        $this->task = $task;
        $this->selectedDueDate = $this->task->getDueDate() ? $task->getDueDate()->format('d-m-Y') : null;
    }

    #[LiveAction]
    public function updateDueDate(): void 
    {
        if(!$this->selectedDueDate) {
            $this->task->setDueDate(null);

            $this->entityManager->flush();

            $this->emit('taskUpdated', [
                'taskId' => $this->task->getId()
            ]);

            return;
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $this->selectedDueDate);

        if(!$date instanceof \DateTimeImmutable) {
            //TODO: Implement Error handling
            return;
        }

        $this->task->setDueDate($date);

        $this->entityManager->flush();

        $this->emit('taskUpdated', [
            'taskId' => $this->task->getId()
        ]);
    }

}
