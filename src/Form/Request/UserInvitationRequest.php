<?php


namespace App\Form\Request;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\UserInvitation;

class UserInvitationRequest
{
    /**
     * @var string|null
     *
     * @Assert\Email(message="Bitte geben Sie eine gültige E-Mail Adresse an.")
     */
    private $email;

    public function __construct(UserInvitation $invitation)
    {
        if (null !== $invitation) {
            $this->extractEntity($invitation);
        }
    }

    /**
     * @param UserInvitation $invitation
     */
    public function fillEntity(UserInvitation $invitation)
    {
        $invitation->setEmail($this->getEmail());
    }

    /**
     * @param UserInvitation $invitation
     */
    public function extractEntity(UserInvitation $invitation)
    {
        $this->setEmail($invitation->getEmail());
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}