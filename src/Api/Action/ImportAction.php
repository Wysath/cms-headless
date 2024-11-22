<?php

namespace App\Api\Action;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use League\Csv\Reader;
use App\Entity\ImportCSV;
use App\Entity\Content;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
readonly class ImportAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    //#[Route('/api/import', name: 'api_import', methods: ['POST'])]
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

    /**
     * @param array<string, string|null> $record
     */
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
}