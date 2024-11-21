<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'user:create',
    description: 'Create a new user',
)]
class UserCreateCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    protected function configure(): void
    {
        $this->addOption('email', null, InputOption::VALUE_REQUIRED, 'The email of the user');
        $this->addOption('password', null, InputOption::VALUE_REQUIRED, 'The password of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $email = $input->getOption('email');
            $password = $input->getOption('password');

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $io->success(sprintf('User %s was successfully created.', $email));

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $io->error(sprintf("Impossible de créer l'utilisateur: %s", $exception->getMessage()));

            return Command::FAILURE;
        }
    }
}