<?php

declare(strict_types=1);

namespace Prony\Controller\Api;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Prony\Dto\MediaElementDto;
//use Prony\Entity\Media;
use Prony\Form\Type\MediaElementType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Talav\Component\Resource\Manager\ManagerInterface;

class MediaUploadAction
{
//    /** @var ManagerInterface */
//    private $mediaManager;
//
//    /** @var ValidatorInterface */
//    private $validator;
//
//    /** @var FormFactoryInterface */
//    private $factory;
//
//    public function __construct(
//        ValidatorInterface $validator,
//        FormFactoryInterface $factory,
//        ManagerInterface $mediaManager)
//    {
//        $this->mediaManager = $mediaManager;
//        $this->validator = $validator;
//        $this->factory = $factory;
//    }
//
    public function __invoke(Request $request)
    {
        return;
//        $mediaElementDto = new MediaElementDto();
//        $form = $this->factory->createNamed('', MediaElementType::class, $mediaElementDto);
//        $form->handleRequest($request);
//        if (!$form->isValid()) {
//            throw new ValidationException($this->validator->validate($mediaElementDto));
//        }
//        $media = $this->createMediaElement($mediaElementDto);
//        $this->mediaManager->update($media, true);
//
//        return $media;
    }

//
//    public function createMediaElement(MediaElementDto $mediaElementDto)
//    {
//        /** @var Media $media */
//        $media = $this->mediaManager->create();
//        $media->setBinaryContent($this->createTempFile($mediaElementDto));
//        $media->setName($mediaElementDto->getFileName());
//        $media->setContext($mediaElementDto->getContext());
//        $media->setProviderName($mediaElementDto->getProvider());
//        $media->setContentType($mediaElementDto->getMimeType());
//
//        return $media;
//    }
//
//    private function createTempFile(MediaElementDto $mediaElementDto)
//    {
//        if (!$binaryContent = $mediaElementDto->getBinaryContent()) {
//            return false;
//        }
//        $temporaryFileName = tempnam(sys_get_temp_dir(), 'upload_action_') . '.' . pathinfo($mediaElementDto->getFileName(), \PATHINFO_EXTENSION);
//        file_put_contents($temporaryFileName, $binaryContent);
//
//        return new UploadedFile(
//            $temporaryFileName,
//            $mediaElementDto->getFileName()
//        );
//    }
}
