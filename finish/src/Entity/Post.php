<?php

namespace App\Entity;

use App\Repository\PostRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $post_body;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $postedAt;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $votes = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPostBody(): ?string
    {
        return $this->post_body;
    }

    public function setPostBody(string $post_body): self
    {
        $this->post_body = $post_body;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->postedAt;
    }

    public function setPostedAt(?\DateTimeInterface $postedAt): self
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    public function getVotes(): int
    {
        return $this->votes;
    }

    public function getVotesInString(): string
    {
        $votesCount = $this->getVotes();

        $prefix = $votesCount >= 0 ? " + " : " - ";

        return sprintf(
            '%s %d',
            $prefix,
            abs($votesCount));
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }


}
