<?php


namespace App\Form\Request;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;

/**
 * Validates a form to create a user.
 *
 * Class UserRequest
 *
 * @package App\Form\Request
 *
 * @AppAssert\UniqueUser(groups={"registration", "edit"})
 */
class UserRequest
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotNull(message="Bitte geben Sie eine E-Mail Adresse an.", groups={"registration", "edit"})
     * @Assert\Email(checkHost=true, message="Bitte geben Sie eine gültige E-Mail Adresse an.", groups={"registration", "edit"})
     */
    private $username;

    /**
     * @var string|null
     *
     * @Assert\Length(min="6", minMessage="Bitte geben Sie ein gültiges Passwort mit mindestens 6 Zeichen an.", groups={"registration"})
     */
    private $password;

    /**
     * @var string|null
     *
     * @Assert\Type(type="string", message="Bitte geben Sie eine Zeichenkette an.", groups={"registration", "edit"})
     * @Assert\NotNull(message="Bitte geben Sie einen Vornamen an.", groups={"registration", "edit"})
     * @Assert\NotBlank(message="Bitte geben Sie einen gültigen Vornamen an.", groups={"registration", "edit"})
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @Assert\Type(type="string", message="Bitte geben Sie eine Zeichenkette an.", groups={"registration", "edit"})
     * @Assert\NotNull(message="Bitte geben Sie einen Nachnamen an.", groups={"registration", "edit"})
     * @Assert\NotBlank(message="Bitte geben Sie einen gültigen Nachnamen an.", groups={"registration", "edit"})
     */
    private $lastName;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var string|null
     */
    private $salt;

    /**
     * @var bool|null
     */
    private $enabledJobNotification;

    /**
     * @var bool|null
     */
    private $enabledPaymentNotification;

    /**
     * @var File|null
     *
     * @Assert\File(mimeTypes={"image/jpeg", "image/png"}, mimeTypesMessage="Bitte geben Sie eine .png oder .jpeg an.", maxSize="4M", maxSizeMessage="Die Datei darf maximal 4 MB groß sein.")
     */
    private $avatarFile;

    public function __construct(User $user)
    {
        if ($user !== null) {
            $this->extractEntity($user);
        }
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string|null $salt
     */
    public function setSalt(?string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @param User $user
     */
    public function extractEntity(User $user): void
    {
        $this->setId($user->getId());
        $this->setUsername($user->getUsername());
        $this->setPassword($user->getPassword());
        $this->setLastName($user->getLastName());
        $this->setFirstName($user->getFirstName());
        $this->setEnabledJobNotification($user->isEnabledJobNotification());
        $this->setEnabledPaymentNotification($user->isEnabledPaymentNotification());
        $this->roles = $user->getRoles();
    }

    /**
     * @param User $user
     * @return User
     */
    public function fillEntity(User $user): User
    {
        $encoder = new NativePasswordEncoder();

        $user->setFirstName($this->getFirstName());
        $user->setLastName($this->getLastName());

        if (!empty($this->getPassword())) {
            // salt is ignored so empty string given
            $encodedPassword = $encoder->encodePassword($this->getPassword(), '');
            $user->setPassword($encodedPassword);
        }

        $user->setEnabledPaymentNotification($this->getEnabledPaymentNotification());
        $user->setEnabledJobNotification($this->getEnabledJobNotification());
        $user->setRoles($this->getRoles());
        $user->setUsername($this->getUsername());
        return $user;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return File|null
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @param File|null $avatarFile
     */
    public function setAvatarFile(?File $avatarFile): void
    {
        $this->avatarFile = $avatarFile;
    }

    /**
     * @return bool|null
     */
    public function getEnabledPaymentNotification(): ?bool
    {
        return $this->enabledPaymentNotification;
    }

    /**
     * @param bool|null $enabledPaymentNotification
     */
    public function setEnabledPaymentNotification(?bool $enabledPaymentNotification): void
    {
        $this->enabledPaymentNotification = $enabledPaymentNotification;
    }

    /**
     * @return bool|null
     */
    public function getEnabledJobNotification(): ?bool
    {
        return $this->enabledJobNotification;
    }

    /**
     * @param bool|null $enabledJobNotification
     */
    public function setEnabledJobNotification(?bool $enabledJobNotification): void
    {
        $this->enabledJobNotification = $enabledJobNotification;
    }
}