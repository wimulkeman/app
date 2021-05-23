<?php

namespace App\Command;

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

        foreach (['alice', 'bob', 'charlie', 'delphine'] as $name) {
            $user = new User();
            $user->setEmail($name.'@example.org');
            $passwordEncoded = $this->passwordEncoder->encodePassword($user, $name);
            $user->setPassword($passwordEncoded);
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        $io->info('Demonstration users created');

        return Command::SUCCESS;
    }
}
