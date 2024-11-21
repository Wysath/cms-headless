<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Content;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class CreateContentProcessor implements ProcessorInterface
{


    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private SluggerInterface $slugger,

    ) {
    }
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Content {

        // Create a new User entity
        $content = new Content();
        $content->title=$data->title;
        $content->coverImage=$data->coverImage;
        $content->metaDescription=$data->metaDescription;

        // Génération du slug à partir du titre
        $slug = (string) $this->slugger->slug($data->title)->lower();
        $content->setSlug($slug);

        $user = $this->security->getUser();
            if ($user) {
                $content->author = $user;
            }

        // Persist the user entity to the database
        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }
}
{

}