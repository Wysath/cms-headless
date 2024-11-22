<?php

namespace App\Api\Action;

use App\Entity\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class UploadAction
{
    private const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    public function __construct(
        private EntityManagerInterface $entityManager,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file instanceof UploadedFile) {
            throw new BadRequestHttpException('No file uploaded');
        }

        // Vérifiez la taille maximale
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new BadRequestHttpException('File size exceeds the maximum limit of 2 MB');
        }

        // Vérifiez les extensions autorisées
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new BadRequestHttpException('Invalid file type. Only JPG and PNG are allowed.');
        }

        // Générer un nom unique tout en conservant le nom de base
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName); // Nettoyer le nom
        $uniqueName = $safeName . '_' . uniqid() . '.' . $extension;

        // Déplacement du fichier
        $file->move($this->projectDir . '/public/medias', $uniqueName);

        // Enregistrement dans la base de données
        $upload = new Upload();
        $upload->path = "/medias/{$uniqueName}";

        $this->entityManager->persist($upload);
        $this->entityManager->flush();

        return new JsonResponse([
            'uuid' => $upload->getUuid(),
            'path' => $upload->path,
        ], JsonResponse::HTTP_CREATED);
    }
}
