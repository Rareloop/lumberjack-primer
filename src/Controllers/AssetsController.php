<?php

namespace Rareloop\Lumberjack\Primer\Controllers;

use App\Responses\Error404Response;
use Composer\InstalledVersions;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;
use RuntimeException;

class AssetsController
{
    public function stylesheet($file)
    {
        return $this->createResponse('css/' . $file, 'text/css');
    }

    public function javascript($file)
    {
        return $this->createResponse('js/' . $file, 'application/javascript');
    }

    public function image($file)
    {
        return $this->createResponse('img/' . $file);
    }

    protected function createResponse($file, $mimeType = null): ResponseInterface
    {
        try {
            $stream = $this->getFileStream($file);
        } catch (Exception $e) {
            return new Error404Response;
        }

        $path = $this->getFilePath($file);

        return new Response($stream, 200, [
            'Content-Type' => $mimeType ?? $this->getMimeTypeOfFile($file),
            'Last-Modified' => date('r', filemtime($path)),
            'Etag' => md5_file($path),
        ]);
    }

    protected function getFileStream($file)
    {
        $path = $this->getFilePath($file);

        if (!file_exists($path)) {
            throw new Exception;
        }

        return fopen($path, 'r+');
    }

    protected function getMimeTypeOfFile($file)
    {
        $path = $this->getFilePath($file);

        return mime_content_type($path);
    }

    protected function getFilePath($file)
    {
        $installPath = InstalledVersions::getInstallPath('rareloop/primer-frontend');

        if ($installPath === null) {
            throw new RuntimeException('Package "rareloop/primer-frontend" is not installed.');
        }

        $path = realpath($installPath) ?: $installPath;

        return $path . '/frontend/dist/' . $file;
    }
}
