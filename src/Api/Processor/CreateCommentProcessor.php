<?php

declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Comments;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;

class CreateCommentProcessor implements ProcessorInterface
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): Comments
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new BadRequestHttpException('Invalid user or not authenticated.');
        }

        $comment = new Comments();
        $comment->comment = $data->comment;
        $comment->author = $user;
        $comment->content = $data->content;

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

}
