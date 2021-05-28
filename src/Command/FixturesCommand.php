<?php

namespace App\Command;

use App\Entity\TodoItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FixturesCommand extends Command
{
    protected static $defaultName = 'app:fixtures';
    protected static $defaultDescription = 'Loads fixtures for development';
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->loadUsers();
        $this->loadTodoItems();
        $this->entityManager->flush();
        $io->info('Fixtures loaded');

        return Command::SUCCESS;
    }

    private function loadUsers(): void
    {
        $userFixtures = [
            ['name' => 'alice'],
            ['name' => 'bob'],
            ['name' => 'charline'],
            ['name' => 'delphine'],
            ['name' => 'eric'],
        ];

        foreach ($userFixtures as $fixture) {
            $name = $fixture['name'];
            $email = $name.'@example.org';

            $user = new User();
            $user->setEmail($email);
            $passwordEncoded = $this->passwordEncoder->encodePassword($user, $name);
            $user->setPassword($passwordEncoded);
            $this->entityManager->persist($user);
        }
    }

    private function loadTodoItems(): void
    {
        $todoItemFixtures = [
            ['position' => 1, 'title' => 'Login form'],
            ['position' => 2, 'title' => 'Change password'],
            ['position' => 3, 'title' => 'Sortable fields'],
            ['position' => 4, 'title' => 'Security based fields'],
        ];

        foreach ($todoItemFixtures as $fixture) {
            $position = $fixture['position'];
            $title = $fixture['title'];

            $item = new TodoItem();
            $item->setPosition($position);
            $item->setTitle($title);
            $this->entityManager->persist($item);
        }
    }
}
