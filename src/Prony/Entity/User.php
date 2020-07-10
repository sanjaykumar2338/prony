<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Talav\UserBundle\Entity\User as BaseUser;

/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"get"}}
 *         }
 *     }
 * )
 */
class User extends BaseUser
{
    /**
     * @Groups({"get", "get-sub"})
     *
     * @var string|null
     */
    protected $firstName;

    /**
     * @Groups({"get", "get-sub"})
     *
     * @var string|null
     */
    protected $lastName;
}
