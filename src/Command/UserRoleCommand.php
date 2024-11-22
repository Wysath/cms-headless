<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'user:role',
    description: 'Add a short description for your command',
)]
class UserRoleCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('role', InputArgument::REQUIRED, 'The role to add or remove')
            ->addArgument('action', InputArgument::REQUIRED, 'The action to perform (add or remove)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');
        $action = $input->getArgument('action');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error('User not found');
            return Command::FAILURE;
        }

        if ($action === 'add') {
            if (!in_array($role, $user->getRoles(), true)) {
                $user->addRole($role);
                $this->entityManager->flush();
                $io->success('Role added successfully');
            } else {
                $io->warning('User already has this role');
            }
        } elseif ($action === 'remove') {
            if (in_array($role, $user->getRoles(), true)) {
                $user->removeRole($role);
                $this->entityManager->flush();
                $io->success('Role removed successfully');
            } else {
                $io->warning('User does not have this role');
            }
        } else {
            $io->error('Invalid action. Use "add" or "remove".');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
