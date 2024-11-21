<?php

namespace App\Api\Action;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use League\Csv\Reader;
use App\Entity\Client;
use App\Entity\Content;


#[AsController]
class ImportClientAction
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $projectDir,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file instanceof UploadedFile) {
            throw new BadRequestHttpException('No file uploaded');
        }

        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $record) {
            // Process each record and save data to the database
            $this->processRecord($record);
        }

        return new JsonResponse(['status' => 'success'], JsonResponse::HTTP_CREATED);
    }

    private function processRecord(array $record): void
    {
        // Check if the required keys exist and are not null
        if (empty($record['title']) || empty($record['meta_description']) || empty($record['content'])) {
            // Skip processing this record if required fields are missing
            return;
        }

        $title = $record['title'];
        $cover = $record['cover'] ?? null;
        $metaTitle = $record['meta_title'] ?? null;
        $metaDescription = $record['meta_description'];
        $content = $record['content'];
        $tags = isset($record['tags']) ? explode(',', $record['tags']) : [];

        // Create and persist entity
        $contentEntity = new Content();
        $contentEntity->setTitle($title);
        $contentEntity->coverImage = $cover;
        $contentEntity->metaDescription = $metaDescription;
        $contentEntity->tags = $tags;

        $this->entityManager->persist($contentEntity);
        $this->entityManager->flush();
    }

    private function saveImage(string $imageUrl): string
    {
        $imageContent = file_get_contents($imageUrl);
        $imageName = uniqid() . '.jpg';
        $imagePath = $this->projectDir . '/public/images/' . $imageName;
        file_put_contents($imagePath, $imageContent);

        return '/images/' . $imageName;
    }
}