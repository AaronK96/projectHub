<?php

namespace App\Twig\Components;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TaskAssigneeModal
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public Task $task;

    #[LiveProp(writable: true)]
    public ?int $selectedAssigneeId = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager, 
        private readonly UserRepository $userRepository
    ) {
        //
    }

    public function mount(Task $task): void 
    {
        $this->task = $task;
        $this->selectedAssigneeId = $task->getAssignee()->getId();
    }

    #[LiveAction]
    public function updateAssignee(): void 
    {
        if(!$this->selectedAssigneeId) {
            $this->task->unassign();

            $this->entityManager->flush();

            $this->emit('taskUpdated', [
                'taskId' => $this->task->getId()
            ]);

            return;
        }

        $user = $this->userRepository->find($this->selectedAssigneeId);

        if(!$user instanceof User) {
            return;
        }

        $this->task->assignTo($user);

        $this->entityManager->flush();

        $this->emit('taskUpdated', [
            'taskId' => $this->task->getId()
        ]);
    }

     /**
     * TODO: nur User aus demselben Team/Workspace laden.
     */
    public function getAssignableUsers(): array
    {
        return $this->userRepository->findBy([], [
            'firstName' => 'ASC',
            'lastName' => 'ASC',
        ]);
    }
}
