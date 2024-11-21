<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\CreateUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em, // autowire comme poour votre command
        private UserPasswordHasherInterface $hasher, // autowire comme poour votre command
    ) {
    }

    /** @param CreateUser $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): User {
        // Create a new User entity
        $user = new User();
        $user->email=$data->email;
        $user->password=$this->hasher->hashPassword($user, $data->password);

        // Persist the user entity to the database
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}