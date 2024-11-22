<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateUploadProcessor implements ProcessorInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): Upload
    {
        if ($data instanceof UploadedFile) {
            $upload = new Upload();
            $upload->path = $data->getClientOriginalName();
            $this->entityManager->persist($upload);
            $this->entityManager->flush();

            return $upload;
        }

        throw new \RuntimeException('No file uploaded');
    }
}