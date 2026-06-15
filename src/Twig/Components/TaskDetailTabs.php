<?php

namespace App\Twig\Components;

use App\Entity\Task;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TaskDetailTabs
{
    use DefaultActionTrait;

    #[LiveProp]
    public Task $task;

    #[LiveProp(writable: true)]
    public string $activeTab = 'overview';

    #[LiveAction]
    public function changeTab(#[LiveArg] string $tab): void {
        $allowedTabs = [
            'overview',
            'subtasks',
            'files',
            'comments',
            'time',
            'activity',
        ];

        if(!in_array($tab, $allowedTabs, true)) {
            return;
        }

        $this->activeTab = $tab;
    }

    public function getTotalCount(): int
    {
        return $this->task->getSubtasks()->count();
    }
}
