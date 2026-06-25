<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CountryRepository;
use App\Slugify\SlugInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection( // Récupère un tableau d'objets => Category[]
            paginationEnabled: false,
            order: [
                'name' => 'ASC',
            ],
            normalizationContext: [
                'groups' => [
                    'country:collection',
                    'country:post',
                ],
            ]
        ),
        new Get( // Récupère UN objet Category
            normalizationContext: [
                'groups' => [
                    'country:item',
                    'country:collection',
                ],
            ]
        ),
        new Post( // Créer la ressource en BDD
            normalizationContext: [ // Donnée renvoyée à la création
                'groups' => [
                    'country:collection',
                    'country:post'
                ],
            ],
            denormalizationContext: [ // JSON à partir duquel on va créer la donnée
                'groups' => 'country:post',
            ],
        ),
        new Put(
            normalizationContext: [ // Donnée renvoyée à la création
                'groups' => [
                    'country:collection',
                    'country:post'
                ],
            ],
            denormalizationContext: [ // JSON à partir duquel on va créer la donnée
                'groups' => 'country:post',
            ]
        ),
        new Patch(
            normalizationContext: [ // Donnée renvoyée à la création
                'groups' => [
                    'country:collection',
                    'country:post'
                ],
            ],
            denormalizationContext: [ // JSON à partir duquel on va créer la donnée
                'groups' => 'country:post',
            ]
        ),
        new Delete()
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        'name' => 'partials',
        'nationality' => 'partials',
    ],
)]
#[ORM\Index(columns: ['nationality'])]
#[ORM\Index(columns: ['slug'])]
class Country implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['country:item', 'publisher:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    #[Assert\NotBlank(message: 'Le code doit être renseigné !')]
    #[Groups(['country:item', 'country:post', 'publisher:item'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom doit être renseigné !')]
    #[Groups(['country:item', 'country:post', 'publisher:item', 'publisher:collection'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La nationalité doit être renseigné !')]
    #[Groups(['country:collection', 'country:post', 'publisher:item'])]
    private ?string $nationality = null;

    #[ORM\Column(length: 255)]
    #[Groups(['country:collection', 'publisher:item', 'publisher:collection'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Groups(['country:collection', 'publisher:item', 'publisher:collection'])]
    private ?string $urlFlag = null;

    /**
     * @var Collection<int, Publisher>
     */
    #[ORM\OneToMany(targetEntity: Publisher::class, mappedBy: 'country')]
    private Collection $publishers;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'country')]
    private Collection $users;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'countries')]
    #[Groups('country:item')]
    private Collection $games;

    public function __construct()
    {
        $this->publishers = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): static
    {
        $this->nationality = $nationality;

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

    public function getUrlFlag(): ?string
    {
        return $this->urlFlag;
    }

    public function setUrlFlag(string $urlFlag): static
    {
        $this->urlFlag = $urlFlag;

        return $this;
    }

    /**
     * @return Collection<int, Publisher>
     */
    public function getPublishers(): Collection
    {
        return $this->publishers;
    }

    public function addPublisher(Publisher $publisher): static
    {
        if (!$this->publishers->contains($publisher)) {
            $this->publishers->add($publisher);
            $publisher->setCountry($this);
        }

        return $this;
    }

    public function removePublisher(Publisher $publisher): static
    {
        if ($this->publishers->removeElement($publisher)) {
            // set the owning side to null (unless already changed)
            if ($publisher->getCountry() === $this) {
                $publisher->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCountry($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCountry() === $this) {
                $user->setCountry(null);
            }
        }

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
            $game->addCountry($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            $game->removeCountry($this);
        }

        return $this;
    }

    #[Groups('country:item')]
    public function getNbGames(): int
    {
        return count($this->games);
    }

    public function getFields(): ?string
    {
        return $this->name;
    }
}
