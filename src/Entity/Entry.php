<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\EntryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntryRepository::class)
 */
class Entry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="entries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Magazine::class, inversedBy="entries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $magazine;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body = null;

    /**
     * @ORM\OneToMany(targetEntity=EntryComment::class, mappedBy="entry")
     */
    private $comments;

    public function __construct(string $title, ?string $url, ?string $body, Magazine $magazine, User $user)
    {

        $this->title    = $title;
        $this->url      = $url;
        $this->body     = $body;
        $this->magazine = $magazine;
        $this->user     = $user;
        $user->addEntry($this);
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getMagazine(): ?Magazine
    {
        return $this->magazine;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Collection|EntryComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(EntryComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setEntry($this);
        }

        return $this;
    }

    public function removeComment(EntryComment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEntry() === $this) {
                $comment->setEntry(null);
            }
        }

        return $this;
    }
}
