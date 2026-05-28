<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\Review\PostReviewController;
use App\Interfaces\CreatedAtInterface;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/reviews/{id}', // id => id d'un jeu et non d'un commentaire !
            controller: PostReviewController::class,
            denormalizationContext: [
                'groups' => 'review:post',
            ],
        )
    ]
)]
#[ORM\Index(columns: ['created_at'])]
class Review implements CreatedAtInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('review:collection')]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['review:collection', 'review:post'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['review:collection', 'review:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['review:collection', 'review:item'])]
    private ?int $downVote = 0;

    #[ORM\Column]
    #[Groups(['review:collection', 'review:item'])]
    private ?int $upVote = 0;

    #[ORM\Column]
    #[Groups(['review:collection', 'review:post'])]
    private ?float $rating = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:collection:user', 'review:item'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:collection:game', 'review:item'])]
    private ?Game $game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDownVote(): ?int
    {
        return $this->downVote;
    }

    public function setDownVote(int $downVote): static
    {
        $this->downVote = $downVote;

        return $this;
    }

    public function getUpVote(): ?int
    {
        return $this->upVote;
    }

    public function setUpVote(int $upVote): static
    {
        $this->upVote = $upVote;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }
}
