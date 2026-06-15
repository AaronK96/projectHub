<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Subtask;
use App\Entity\Task;
use App\Entity\Team;
use App\Entity\TeamMembership;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        /**
         * USERS
         */
        $max = $this->createUser(
            email: 'max@example.com',
            firstName: 'Max',
            lastName: 'Mustermann',
            avatarUrl: 'https://i.pravatar.cc/150?img=12',
            manager: $manager,
        );

        $lisa = $this->createUser(
            email: 'lisa@example.com',
            firstName: 'Lisa',
            lastName: 'Schneider',
            avatarUrl: 'https://i.pravatar.cc/150?img=5',
            manager: $manager,
        );

        $thomas = $this->createUser(
            email: 'thomas@example.com',
            firstName: 'Thomas',
            lastName: 'Weber',
            avatarUrl: 'https://i.pravatar.cc/150?img=8',
            manager: $manager,
        );

        $sarah = $this->createUser(
            email: 'sarah@example.com',
            firstName: 'Sarah',
            lastName: 'Klein',
            avatarUrl: 'https://i.pravatar.cc/150?img=10',
            manager: $manager,
        );

        /**
         * TEAM
         */
        $team = new Team();
        $team
            ->setName('ProjectHub Demo Team')
            ->setSlug('projecthub-demo-team');

        $manager->persist($team);

        /**
         * TEAM MEMBERSHIPS
         */
        $this->createMembership($team, $max, TeamMembership::ROLE_OWNER, $manager);
        $this->createMembership($team, $lisa, TeamMembership::ROLE_ADMIN, $manager);
        $this->createMembership($team, $thomas, TeamMembership::ROLE_MEMBER, $manager);
        $this->createMembership($team, $sarah, TeamMembership::ROLE_MEMBER, $manager);

        /**
         * PROJECTS
         */
        $websiteRedesign = $this->createProject(
            team: $team,
            owner: $max,
            name: 'Website Redesign',
            description: 'Redesign der Unternehmenswebsite mit neuem Layout, besserer UX und moderner technischer Basis.',
            status: Project::STATUS_IN_PROGRESS,
            deadline: '+17 days',
            manager: $manager,
        );

        $mobileApp = $this->createProject(
            team: $team,
            owner: $lisa,
            name: 'Mobile App',
            description: 'Konzeption und Umsetzung einer mobilen App für bestehende ProjectHub-Nutzer.',
            status: Project::STATUS_REVIEW,
            deadline: '+30 days',
            manager: $manager,
        );

        $marketingCampaign = $this->createProject(
            team: $team,
            owner: $sarah,
            name: 'Marketing Kampagne',
            description: 'Planung einer Kampagne für den Launch der neuen ProjectHub Landingpage.',
            status: Project::STATUS_IN_PROGRESS,
            deadline: '+24 days',
            manager: $manager,
        );

        $securityAudit = $this->createProject(
            team: $team,
            owner: $thomas,
            name: 'Security Audit',
            description: 'Überprüfung sicherheitsrelevanter Bereiche wie Authentifizierung, Rechteprüfung und API-Zugriffe.',
            status: Project::STATUS_DONE,
            deadline: '-5 days',
            manager: $manager,
        );

        /**
         * TASKS - WEBSITE REDESIGN
         */
        $loginTask = $this->createTask(
            project: $websiteRedesign,
            createdBy: $max,
            assignee: $lisa,
            title: 'Login Seite erstellen',
            description: 'Design und Implementierung der Login Seite inklusive Validierung, Social Login und Passwort vergessen Funktion.',
            status: Task::STATUS_IN_PROGRESS,
            priority: Task::PRIORITY_HIGH,
            dueDate: '+10 days',
            manager: $manager,
        );

        $this->createSubtask(
            task: $loginTask,
            title: 'Wireframe erstellen',
            description: 'Grobe Struktur der Login-Seite erstellen, inklusive Formularbereich, CTA und Link zur Passwort-Zurücksetzung.',
            isCompleted: true,
            position: 1,
            manager: $manager,
        );

        $this->createSubtask(
            task: $loginTask,
            title: 'UI Design in Figma',
            description: 'Finales Login-Design mit Farben, Abständen, Formularfeldern und Fehlerzuständen vorbereiten.',
            isCompleted: true,
            position: 2,
            manager: $manager,
        );

        $this->createSubtask(
            task: $loginTask,
            title: 'Frontend Umsetzung',
            description: 'Twig Template und CSS für die Login-Seite implementieren und responsive optimieren.',
            isCompleted: false,
            position: 3,
            manager: $manager,
        );

        $this->createSubtask(
            task: $loginTask,
            title: 'Formularvalidierung einbauen',
            description: 'Fehlermeldungen für ungültige E-Mail, leeres Passwort und fehlgeschlagene Anmeldung anzeigen.',
            isCompleted: false,
            position: 4,
            manager: $manager,
        );

        $this->createTask(
            project: $websiteRedesign,
            createdBy: $max,
            assignee: $thomas,
            title: 'API Integration vorbereiten',
            description: 'Schnittstellen für Projekt- und Taskdaten vorbereiten, damit das Frontend später dynamisch angebunden werden kann.',
            status: Task::STATUS_TODO,
            priority: Task::PRIORITY_MEDIUM,
            dueDate: '+14 days',
            manager: $manager,
        );

        $this->createTask(
            project: $websiteRedesign,
            createdBy: $lisa,
            assignee: $lisa,
            title: 'Responsive Design prüfen',
            description: 'Alle Hauptseiten auf Desktop, Tablet und Mobile prüfen und Layoutfehler beheben.',
            status: Task::STATUS_REVIEW,
            priority: Task::PRIORITY_MEDIUM,
            dueDate: '+7 days',
            manager: $manager,
        );

        $this->createTask(
            project: $websiteRedesign,
            createdBy: $max,
            assignee: $sarah,
            title: 'Content Struktur überarbeiten',
            description: 'Startseite, Feature-Bereiche und CTA-Texte für bessere Conversion überarbeiten.',
            status: Task::STATUS_DONE,
            priority: Task::PRIORITY_LOW,
            dueDate: '-2 days',
            manager: $manager,
        );

        /**
         * TASKS - MOBILE APP
         */
        $onboardingTask = $this->createTask(
            project: $mobileApp,
            createdBy: $lisa,
            assignee: $lisa,
            title: 'Onboarding Flow designen',
            description: 'Erstellung eines einfachen Onboarding-Flows für neue Nutzer mit drei Einführungsschritten.',
            status: Task::STATUS_REVIEW,
            priority: Task::PRIORITY_HIGH,
            dueDate: '+6 days',
            manager: $manager,
        );

        $this->createSubtask(
            task: $onboardingTask,
            title: 'Screens definieren',
            description: 'Die wichtigsten Onboarding-Screens festlegen und Reihenfolge bestimmen.',
            isCompleted: true,
            position: 1,
            manager: $manager,
        );

        $this->createSubtask(
            task: $onboardingTask,
            title: 'Microcopy schreiben',
            description: 'Kurze Texte für Erklärungen, Buttons und Hinweise erstellen.',
            isCompleted: false,
            position: 2,
            manager: $manager,
        );

        $this->createTask(
            project: $mobileApp,
            createdBy: $max,
            assignee: $thomas,
            title: 'Push Notification Konzept',
            description: 'Konzept für Erinnerungen, Task-Updates und Projektbenachrichtigungen erstellen.',
            status: Task::STATUS_IN_PROGRESS,
            priority: Task::PRIORITY_MEDIUM,
            dueDate: '+12 days',
            manager: $manager,
        );

        $this->createTask(
            project: $mobileApp,
            createdBy: $thomas,
            assignee: $thomas,
            title: 'API Authentifizierung testen',
            description: 'JWT/API Token Flow prüfen und mögliche Fehlerfälle dokumentieren.',
            status: Task::STATUS_DONE,
            priority: Task::PRIORITY_HIGH,
            dueDate: '-1 day',
            manager: $manager,
        );

        /**
         * TASKS - MARKETING
         */
        $this->createTask(
            project: $marketingCampaign,
            createdBy: $sarah,
            assignee: $sarah,
            title: 'Landingpage Texte schreiben',
            description: 'Texte für Hero Section, Feature Cards und Pricing-Bereich vorbereiten.',
            status: Task::STATUS_IN_PROGRESS,
            priority: Task::PRIORITY_MEDIUM,
            dueDate: '+9 days',
            manager: $manager,
        );

        $this->createTask(
            project: $marketingCampaign,
            createdBy: $sarah,
            assignee: $max,
            title: 'Newsletter Kampagne planen',
            description: 'Zielgruppen, Versandzeitpunkte und Inhalte für die erste Newsletter-Serie definieren.',
            status: Task::STATUS_TODO,
            priority: Task::PRIORITY_LOW,
            dueDate: '+18 days',
            manager: $manager,
        );

        /**
         * TASKS - SECURITY
         */
        $this->createTask(
            project: $securityAudit,
            createdBy: $thomas,
            assignee: $thomas,
            title: 'Rollenrechte prüfen',
            description: 'Prüfen, ob Nutzer nur Zugriff auf Projekte und Tasks ihrer Teams haben.',
            status: Task::STATUS_DONE,
            priority: Task::PRIORITY_URGENT,
            dueDate: '-3 days',
            manager: $manager,
        );

        $this->createTask(
            project: $securityAudit,
            createdBy: $thomas,
            assignee: $max,
            title: 'Rate Limiting konfigurieren',
            description: 'API-Endpunkte gegen zu viele Requests absichern.',
            status: Task::STATUS_DONE,
            priority: Task::PRIORITY_HIGH,
            dueDate: '-1 day',
            manager: $manager,
        );

        $manager->flush();
    }

    private function createUser(
        string $email,
        string $firstName,
        string $lastName,
        ?string $avatarUrl,
        ObjectManager $manager,
    ): User {
        $user = new User();
        $user
            ->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setAvatarUrl($avatarUrl);

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'password')
        );

        $manager->persist($user);

        return $user;
    }

    private function createMembership(
        Team $team,
        User $user,
        string $role,
        ObjectManager $manager,
    ): TeamMembership {
        $membership = new TeamMembership();
        $membership
            ->setTeam($team)
            ->setUser($user)
            ->setRole($role);

        $manager->persist($membership);

        return $membership;
    }

    private function createProject(
        Team $team,
        User $owner,
        string $name,
        ?string $description,
        string $status,
        string $deadline,
        ObjectManager $manager,
    ): Project {
        $project = new Project();
        $project
            ->setTeam($team)
            ->setOwner($owner)
            ->setName($name)
            ->setDescription($description)
            ->setStatus($status)
            ->setDeadline(new \DateTimeImmutable($deadline));

        $manager->persist($project);

        return $project;
    }

    private function createTask(
        Project $project,
        User $createdBy,
        ?User $assignee,
        string $title,
        ?string $description,
        string $status,
        string $priority,
        string $dueDate,
        ObjectManager $manager,
    ): Task {
        $task = new Task();
        $task
            ->setProject($project)
            ->setCreatedBy($createdBy)
            ->setAssignee($assignee)
            ->setTitle($title)
            ->setDescription($description)
            ->setStatus($status)
            ->setPriority($priority)
            ->setDueDate(new \DateTimeImmutable($dueDate));

        $manager->persist($task);

        return $task;
    }

    private function createSubtask(
        Task $task,
        string $title,
        ?string $description,
        bool $isCompleted,
        int $position,
        ObjectManager $manager,
    ): Subtask {
        $subtask = new Subtask();
        $subtask
            ->setTask($task)
            ->setTitle($title)
            ->setDescription($description)
            ->setIsCompleted($isCompleted)
            ->setPosition($position);

        $manager->persist($subtask);

        return $subtask;
    }
}