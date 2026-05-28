<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\UserOwnGames\UserOwnGamesPostController;
use App\Interfaces\CreatedAtInterface;
use App\Repository\UserOwnGameRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserOwnGameRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/user_own_games/{gameId}',
            uriVariables: [
                'gameId' => new Link(
                    toProperty: 'id',
                    fromClass: Game::class
                )
            ],
            controller: UserOwnGamesPostController::class,
            denormalizationContext: [
                'groups' => '',
            ],
        ),
    ]
)]
#[ORM\Index(columns: ['created_at'])]
#[ORM\Index(columns: ['game_time'])]
class UserOwnGame implements CreatedAtInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('userOwnGame:item')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups('userOwnGame:item')]
    private ?int $gameTime = 0;

    #[ORM\Column(nullable: true)]
    #[Groups('userOwnGame:item')]
    private ?\DateTimeImmutable $lastUsedAt = null;

    #[ORM\Column]
    #[Groups('userOwnGame:item')]
    private ?bool $isInstalled = false;

    #[ORM\ManyToOne(inversedBy: 'ownedByUser')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userOwnGame:item')]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'ownedGames')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('userOwnGame:item')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGameTime(): ?int
    {
        return $this->gameTime;
    }

    public function setGameTime(int $gameTime): static
    {
        $this->gameTime = $gameTime;

        return $this;
    }

    public function getLastUsedAt(): ?\DateTimeImmutable
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(?\DateTimeImmutable $lastUsedAt): static
    {
        $this->lastUsedAt = $lastUsedAt;

        return $this;
    }

    public function isInstalled(): ?bool
    {
        return $this->isInstalled;
    }

    public function setIsInstalled(bool $isInstalled): static
    {
        $this->isInstalled = $isInstalled;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
