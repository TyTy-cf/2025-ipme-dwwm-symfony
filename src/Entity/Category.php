<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CategoryRepository;
use App\Slugify\SlugInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection( // Récupère un tableau d'objets => Category[]
            normalizationContext:[
                'groups' => [
                    'category:collection',
                    'category:post',
                ],
            ]
        ),
        new Get( // Récupère UN objet Category
            normalizationContext: [
                'groups' => [
                    'category:item',
                    'game:collection',
                ],
            ]
        ),
        new Post( // Créer la ressource en BDD
            normalizationContext: [ // Donnée renvoyée à la création
                'groups' => [
                    'category:collection',
                    'category:post'
                ],
            ],
            denormalizationContext: [ // JSON à partir duquel on va créer la donnée
                'groups' => 'category:post',
            ]
        ),
        new Put(
            normalizationContext: [ // Donnée renvoyée à la création
                'groups' => [
                    'category:collection',
                    'category:post'
                ],
            ],
            denormalizationContext: [ // JSON à partir duquel on va créer la donnée
                'groups' => 'category:post',
            ]
        ),
        new Patch(
            normalizationContext: [ // Donnée renvoyée à la création
                'groups' => [
                    'category:collection',
                    'category:post'
                ],
            ],
            denormalizationContext: [ // JSON à partir duquel on va créer la donnée
                'groups' => 'category:post',
            ]
        ),
        new Delete()
    ]
)]
#[ORM\Index(columns: ['name'])]
#[ORM\Index(columns: ['slug'])]
class Category implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('category:collection')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('category:post')]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category:post', 'category:item', 'category:collection'])]
    #[Assert\NotBlank(message: 'Le nom doit être renseigné !')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'La catégorie doit faire au moins 3 caractères !',
        maxMessage: 'La catégorie doit faire maximum 255 caractères !',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('category:collection')]
    private ?string $slug = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'categories')]
    #[Groups('category:item')]
    private Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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
            $game->addCategory($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            $game->removeCategory($this);
        }

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->name;
    }
}
