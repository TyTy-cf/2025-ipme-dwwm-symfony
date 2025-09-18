<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\PublisherRepository;
use App\Slugify\SlugInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
#[ApiResource(operations: [
    new Get(
        normalizationContext: [
            'groups' => [
                'publisher:item',
                'game:collection',
            ]
        ]
    ),
    new GetCollection(
        order: ['createdAt' => 'DESC'],
        normalizationContext: [
            'groups' => [
                'publisher:collection',
            ]
        ]
    ),
    new Post(
        normalizationContext: [
            'groups' => 'publisher:item',
        ],
        denormalizationContext: [
            'groups' => 'publisher:post',
        ]
    ),
    new Put(
        normalizationContext: [
            'groups' => 'publisher:item',
        ],
        denormalizationContext: [
            'groups' => 'publisher:post',
        ]
    ),
    new Patch(
        normalizationContext: [
            'groups' => 'publisher:item',
        ],
        denormalizationContext: [
            'groups' => 'publisher:post',
        ]
    )
])]
#[ORM\Index(columns: ['created_at'])]
#[ORM\Index(columns: ['name'])]
#[ORM\Index(columns: ['slug'])]
class Publisher implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['publisher:item', 'publisher:collection'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['publisher:post', 'publisher:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['publisher:post', 'publisher:item', 'publisher:collection'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['publisher:item', 'publisher:collection'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Groups(['publisher:post', 'publisher:item'])]
    private ?string $website = null;

    #[ORM\ManyToOne(inversedBy: 'publishers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['publisher:post', 'publisher:item', 'publisher:collection'])]
    private ?Country $country = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'publisher', cascade: ["remove"])]
    #[Groups('publisher:item')]
    private Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): static
    {
        $this->website = $website;

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
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setPublisher($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPublisher() === $this) {
                $game->setPublisher(null);
            }
        }

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->name;
    }
}
