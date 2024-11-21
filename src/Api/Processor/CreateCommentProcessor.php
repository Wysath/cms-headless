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

final readonly class CreateCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
    ) {
    }

    /**
     * @param Comments $data
     * @param Operation $operation
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     * @return Comments
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Comments {
        // Create a new Comments entity
        $comment = new Comments();
        $comment->comment = $data->comment;

        // Set the author
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $comment->author = $user;
        } else {
            throw new BadRequestHttpException('User not authenticated or invalid user type');
        }

        // Extract the content ID from the URL
        if (isset($data->content)) {
            $contentId = (string) $data->content->getId();
            $content = $this->em->getRepository(Content::class)->find($contentId);
            if ($content) {
                $comment->content = $content;
            } else {
                throw new BadRequestHttpException('Content not found');
            }
        } else {
            throw new BadRequestHttpException('Content is required');
        }

        // Persist the comment entity to the database
        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}