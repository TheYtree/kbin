<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\ContentInterface;
use App\Entity\Traits\CreatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="log_type", type="text")
 * @ORM\DiscriminatorMap({
 *   "entry_delete": "MagazineLogEntryDelete",
 *   "entry_comment_delete": "MagazineLogEntryCommentDelete",
 *   "post_delete": "MagazineLogPostDelete",
 *   "post_comment_delete": "MagazineLogPostCommentDelete",
 *   "ban": "MagazineLogBan",
 * })
 */
abstract class MagazineLog
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Magazine", inversedBy="logs")
     */
    public Magazine $magazine;
    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     */
    public User $user;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    public function __construct(Magazine $magazine, User $user)
    {
        $this->magazine = $magazine;
        $this->user     = $user;

        $this->createdAtTraitConstruct();
    }

    abstract public function getSubject(): ContentInterface|null;

    abstract public function clearSubject(): MagazineLog;

    abstract public function getType(): string;
}
