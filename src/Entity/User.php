<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\Api\User\UserMeController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/user/me',
            controller: UserMeController::class,
            normalizationContext: [
                'groups' => ['user:item'],
            ]
        )
    ]
)]
#[ORM\Index(columns: ['created_at'])]
#[ORM\Index(columns: ['name'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups('user:item')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['review:collection:user', 'user:item', 'review:item', 'userOwnGame:item'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('user:item')]
    private ?string $nickname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['review:collection:user', 'user:item', 'review:item'])]
    private ?string $profileImage = null;

    #[ORM\Column]
    #[Groups('user:item')]
    private ?int $wallet = 0;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Country $country = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\ManyToMany(targetEntity: Game::class)]
    #[ORM\JoinTable(name: 'wishlist')]
    private Collection $wantedGames;

    /**
     * @var Collection<int, UserOwnGame>
     */
    #[ORM\OneToMany(targetEntity: UserOwnGame::class, mappedBy: 'user')]
    private Collection $ownedGames;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'user')]
    private Collection $reviews;

    public function __construct()
    {
        $this->wantedGames = new ArrayCollection();
        $this->ownedGames = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $profileImage): static
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    public function getWallet(): ?int
    {
        return $this->wallet;
    }

    public function setWallet(int $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getWantedGames(): Collection
    {
        return $this->wantedGames;
    }

    public function addWantedGame(Game $wantedGame): static
    {
        if (!$this->wantedGames->contains($wantedGame)) {
            $this->wantedGames->add($wantedGame);
        }

        return $this;
    }

    public function removeWantedGame(Game $wantedGame): static
    {
        $this->wantedGames->removeElement($wantedGame);

        return $this;
    }

    /**
     * @return Collection<int, UserOwnGame>
     */
    public function getOwnedGames(): Collection
    {
        return $this->ownedGames;
    }

    public function addOwnedGame(UserOwnGame $ownedGame): static
    {
        if (!$this->ownedGames->contains($ownedGame)) {
            $this->ownedGames->add($ownedGame);
            $ownedGame->setUser($this);
        }

        return $this;
    }

    public function removeOwnedGame(UserOwnGame $ownedGame): static
    {
        if ($this->ownedGames->removeElement($ownedGame)) {
            // set the owning side to null (unless already changed)
            if ($ownedGame->getUser() === $this) {
                $ownedGame->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

}
