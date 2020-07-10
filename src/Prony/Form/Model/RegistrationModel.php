<?php

declare(strict_types=1);

namespace Prony\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Talav\UserBundle\Validator\Constraints\RegisteredUser;

final class RegistrationModel
{
    /**
     * @Assert\NotBlank(message="talav.email.blank")
     * @Assert\Email(message="talav.email.invalid", mode="strict")
     * @RegisteredUser(message="talav.email.already_used")
     *
     * @var string|null
     */
    private $email;

    /**
     * @Assert\NotBlank(message="prony.registration.first_name.required")
     * @Assert\Length(min=1, max=250)
     *
     * @var string|null
     */
    private $firstName;

    /**
     * @Assert\NotBlank(message="prony.registration.last_name.required")
     * @Assert\Length(min=1, max=250)
     *
     * @var string|null
     */
    private $lastName;

    /**
     * @Assert\NotBlank(message="talav.password.blank")
     * @Assert\Length(min=4, minMessage="talav.password.short", max=254, maxMessage="talav.password.long")
     *
     * @var string|null
     */
    private $plainPassword;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
