<?php

declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private SluggerInterface $slugger,
    ) {
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     * @return Content
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Content {
        // Create a new Content entity
        $content = new Content();
        $content->title = $data->title;
        $content->coverImage = $data->coverImage;
        $content->metaDescription = $data->metaDescription;

        // Génération du slug à partir du titre
        $slug = (string) $this->slugger->slug($data->title)->lower();
        $content->setSlug($slug);

        $content->defineUuid();

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $content->author = $user;
        } else {
            throw new BadRequestHttpException('User not authenticated or invalid user type');
        }

        // Persist the content entity to the database
        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }
}
