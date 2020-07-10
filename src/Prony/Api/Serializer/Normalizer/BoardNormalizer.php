<?php

declare(strict_types=1);

namespace Prony\Api\Serializer\Normalizer;

use ApiPlatform\Core\Api\IriConverterInterface;
use Prony\Entity\Board;
use Prony\Provider\WorkspaceProvider;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class BoardNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    /** @var DenormalizerInterface|NormalizerInterface */
    private $decorated;

    /** @var WorkspaceProvider */
    private $workspaceProvider;

    /** @var IriConverterInterface */
    private $iriConverter;

    public function __construct(NormalizerInterface $decorated, IriConverterInterface $iriConverter, WorkspaceProvider $workspaceProvider)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
        $this->iriConverter = $iriConverter;
        $this->workspaceProvider = $workspaceProvider;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->decorated->normalize($object, $format, $context);

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if ($class == Board::class && !isset($data['workspace'])) {
            $data['workspace'] = $this->iriConverter->getIriFromItem($this->workspaceProvider->getWorkspace());
        }

        return $this->decorated->denormalize($data, $class, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}
