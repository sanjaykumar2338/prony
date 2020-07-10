<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;

/**
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     normalizationContext={
 *         "groups"={"read"}
 *     },
 *     collectionOperations={
 *         "post"={
 *             "controller"=Prony\Controller\Api\MediaUploadAction::class,
 *             "deserialize"=false,
 *             "validation_groups"={"post"},
 *             "normalization_context"={"groups"={"write-response"}},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get"
 *     }
 * )
 */
class Media implements ResourceInterface
{
    use ResourceTrait;

    /**
     * @var File|null
     *
     * @Assert\NotNull
     */
    public $file;

    /**
     * @var array
     *
     * @Groups({"write-response"})
     */
    protected $formats;

    /**
     * @return array
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * @param array $format
     */
    public function addFormat($format)
    {
        $this->formats[] = $format;
    }
}
