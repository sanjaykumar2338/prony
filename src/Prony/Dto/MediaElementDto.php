<?php

declare(strict_types=1);

namespace Prony\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class MediaElementDto
{
    /**
     * @var string
     *
     * @Assert\NotNull
     */
    private $fileName;

    /**
     * @var UploadedFile
     *
     * @Assert\NotBlank()
     * @Assert\File(
     *     maxSize = "5m",
     *     mimeTypes = {"image/pjpeg", "image/jpeg", "image/png", "image/x-png"},
     *     mimeTypesMessage="prony.media.file.type"
     * )
     */
    private $file;

    /** @var array */
    private $attributes = [];

    /** @var string */
    private $provider = 'sonata.media.provider.image';

    /** @var string */
    private $context = 'post';

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getMimeType()
    {
        if (!$this->attributes) {
            $this->extractAttributes();
        }

        return $this->attributes['mimeType'];
    }

    public function getBinaryContent()
    {
        if (!$this->attributes) {
            $this->extractAttributes();
        }

        return $this->attributes['binaryContent'];
    }

    public function getBase64Content()
    {
        if (!$this->attributes) {
            $this->extractAttributes();
        }

        return $this->attributes['base64Content'];
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        $this->fileName = $file->getClientOriginalName();
    }

    private function extractAttributes()
    {
        $this->attributes = [
            'base64Content' => '',
            'binaryContent' => '',
            'mimeType' => '',
            'fileExtension' => '',
        ];
        $binaryContent = file_get_contents($this->getFile()->getPathname());
        $this->attributes['binaryContent'] = $binaryContent;
        $this->attributes['base64Content'] = base64_encode($binaryContent);
        $this->attributes['mimeType'] = $this->getFile()->getMimeType();
        [, $this->attributes['fileExtension']] = explode('/', $this->getFile()->getMimeType());
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function setContext(string $context): void
    {
        $this->context = $context;
    }
}
