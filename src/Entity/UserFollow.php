<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Repository\UserFollowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="user_follows_idx",
 *         columns={"follower_id", "following_id"}
 *     )
 * })
 * @ORM\Entity(repositoryClass=UserFollowRepository::class)
 */
class UserFollow
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="follows")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public ?User $follower;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followers")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public ?User $following;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    public function __construct(User $follower, User $following)
    {
        $this->createdAtTraitConstruct();

        $this->follower  = $follower;
        $this->following = $following;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __sleep()
    {
        return [];
    }
}
