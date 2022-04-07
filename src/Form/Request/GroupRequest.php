<?php


namespace App\Form\Request;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;
use App\Entity\Group;

/**
 * Class GroupRequest
 *
 * @package App\Form\Request
 *
 * @AppAssert\UniqueGroup()
 */
class GroupRequest
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Bitte geben Sie einen Gruppennamen an.")
     * @Assert\NotNull(message="Bitte geben Sie einen Gruppennamen an.")
     * @Assert\Type(type="string", message="Bitte geben Sie eine Zeichenkette an.")
     * @Assert\Length(max="255", maxMessage="Bitte geben Sie maximal 255 Zeichen ein.")
     */
    private $name;

    /**
     * @var float|null
     */
    private $budget;

    /**
     * @var string|null
     */
    private $street;

    /**
     * @var string|null
     */
    private $housenumber;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $zip;

    /**
     * GroupRequest constructor.
     *
     * @param Group|null $group
     */
    public function __construct(Group $group = null)
    {
        if (null !== $group) {
            $this->extractEntity($group);
        }
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getHousenumber(): ?string
    {
        return $this->housenumber;
    }

    /**
     * @param string|null $housenumber
     */
    public function setHousenumber(?string $housenumber): void
    {
        $this->housenumber = $housenumber;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    public function extractEntity(Group $group)
    {
        $this->id = $group->getId();
        $this->setName($group->getName());
        $this->setBudget($group->getBudget());
        $this->setCity($group->getCity());
        $this->setHousenumber($group->getHousenumber());
        $this->setStreet($group->getStreet());
        $this->setZip($group->getZip());
    }

    public function fillEntity(Group $group)
    {
        $group->setZip($this->getZip());
        $group->setName($this->getName());
        $group->setBudget($this->getBudget());
        $group->setStreet($this->getStreet());
        $group->setCity($this->getCity());
        $group->setHousenumber($this->getHousenumber());
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getBudget(): ?float
    {
        return $this->budget;
    }

    /**
     * @param float|null $budget
     */
    public function setBudget(?float $budget): void
    {
        $this->budget = $budget;
    }

}