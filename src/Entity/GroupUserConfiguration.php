<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Represents a user and group relation with configurations.
 *
 * Class GroupUserConfiguration
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\GroupUserConfigurationRepository")
 * @ORM\Table(name="hj_group_user_configuration")
 */
class GroupUserConfiguration
{
    use TimestampableEntity;

    public const GROUP_ADMINISTRATOR = 'GROUP_ADMIN';
    public const GROUP_MEMBER = 'GROUP_MEMBER';

    /**
     * @var User
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="groups")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Group
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="groupConfigurations")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, length=128)
     */
    private $role;

    /**
     * GroupUserConfiguration constructor.
     *
     * @param Group $group
     * @param User $user
     * @param string $role
     */
    public function __construct(Group $group, User $user, string $role)
    {
        $this->user = $user;
        $this->group = $group;
        $this->role = $role;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @param bool $stop
     */
    public function setGroup(Group $group, bool $stop = false): void
    {
        if (!$stop) {
            if ($group === null) {
                $this->group->removeGroupUserConfiguration($this, true);
            } else {
                $group->addGroupUserConfiguration($this, true);
            }
        }

        $this->group = $group;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @param bool $stop
     */
    public function setUser(User $user, bool $stop = false): void
    {
        if (!$stop) {
            if ($user === null) {
                $this->user->removeGroupUserConfiguration($this, true);
            } else {
                $user->addGroupUserConfiguration($this, true);
            }
        }

        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getRoleLabel(): string
    {
        switch($this->role) {
            case self::GROUP_ADMINISTRATOR:
                return 'Administrator';
            case self::GROUP_MEMBER:
                return 'Mitglied';
            default:
                return '';
        }
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

}