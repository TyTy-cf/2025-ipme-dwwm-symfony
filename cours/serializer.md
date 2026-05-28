
## Serializer Symfony


#### 1.1 Injection


On injecte l'objet `SerializerInterface` dans le constructeur de la classe ou dans une route d'un contrôleur.


### 1.2 Sérialiser


On utilise la fonction `serialize` qui prend jusqu'à 3 paramètres :
- data : la donnée à sérialiser
- format : le format de sortie
- context : le contexte de sérialisation

Exemple, sans contexte :

```php
$existingGames[] =  $this->serializer->serialize(
    new GameDTO(
        $game->getName(),
        $game->getSlug(),
        $game->getThumbnailCover(),
        $game->getThumbnailCoverLink(),
        $game->getPrice()
    ),
    'json'
);
```

Exemple, avec contexte, se basant sur les attributs `#[Groups]` présents dans les entités :

```php
$existingGames[] =  $this->serializer->serialize(
    $game,
    'json',
    ['groups' => ['game:collection']]
);
```

Ainsi, le sérialiser va récupérer les attributs de l'entité `Game` qui ont le groupe `game:collection` et les sérialiser.


### 1.3 Désérialiser


On utilise la fonction `deserialize` qui prend jusqu'à 4 paramètres :
- data : la donnée à désérialiser
- type : le type de la donnée (la classe à instancier en sortie)
- format : le format de la donnée
- context : le contexte de désérialisation

Exemple, sans contexte :

```php
$gameDTO = $this->serializer->deserialize(
    $jsonGame,
    GameDTO::class,
    'json'
);
```

Exemple, avec contexte, se basant sur les attributs `#[Groups]` présents dans les entités :


```php
$game = $this->serializer->deserialize(
    $jsonGame,
    Game::class,
    'json',
    ['groups' => ['game:collection']]
);
```

