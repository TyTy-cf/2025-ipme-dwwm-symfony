<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class FileUploaderService
{

    /**
     * FileUploader constructor.
     * @param string $uploadDir
     * @param string $pathPublicDir
     * @param string $pathPublicUploadsDir complete path to default directory of uploaded files
     */
    public function __construct(
        private string $uploadDir,
        private string $pathPublicDir,
        private string $pathPublicUploadsDir,
    )
    {
    }

    /**
     * @param UploadedFile $uploadedFile the file to upload on the server
     * @param string $namespace the dir location to upload the file
     * @return string
     */
    public function uploadFile(UploadedFile $uploadedFile, string $namespace = ''): string
    {
        $destination = $this->pathPublicUploadsDir . $namespace;
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFilename);
        return $this->uploadDir . $namespace . '/' . $newFilename;
    }

    public function deleteFileFromDisk(?string $file): void
    {
        if ($file === null) return;

        $fs = new Filesystem();
        $targetDir = $this->pathPublicDir . $file;
        $fs->remove($targetDir);
    }

}
