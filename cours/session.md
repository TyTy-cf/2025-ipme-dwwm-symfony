
## Session Symfony


### 1. Récupérer la session


#### 1.1 Depuis un contrôleur


On peut récupérer la session depuis l'objet `SessionInterface` **UNIQUEMENT** depuis une fonction d'un contrôleur.
On ne peut pas récupérer la session en "globale" si elle est injectée dans un constructeur de contrôleur.

```php
#[Route('/jeux', name: 'app_index_game')]
    public function show(
        SessionInterface $session
    ): Response
    {
```


#### 1.2 En dehors d'un contrôleur


Il peut arriver que vous aillez besoin de la session en dehors d'un contrôleur.
Mais vous ne pouvez pas l'injecter directement dans le constructeur, et c'est un peu pénible de devoir la passer en paramètre à chaque fois...

Il existe l'objet `RequestStack`qui lui est injectable dans un constructeur et permet de récupérer la session.


##### 1.2.1 `RequestStack`


C'est un objet qui est donc injectable, en dehors des fonctions "de route" et permet de récupérer la session ou encore la request.


```php
class ExampleService
{

    public function __construct(
        private RequestStack $requestStack,
    )
    { }

    private function getRequest(): Request {
        return $this->requestStack->getCurrentRequest();
    }

    private function getSession(): SessionInterface {
        return $this->requestStack->getSession();
    }

}
```

### 2. Utiliser la session


Il existe plusieurs fonctions via l'objet Session, qui nous permette de l'utiliser :


- Ajouter un élément dans la session :
  - Le premier paramètre est la clé à ajouter en session
  - Le deuxième paramètre est la valeur
```php
$this->getSession()->set(self::CART_GAMES, $game->getId());
```

- Récupérer un élément de la session, via sa clé :
```php
$this->getSession()->get(self::CART_GAMES);
```

- Est-ce que la clé existe dans la sesion ?
```php
$this->getSession()->has(self::CART_GAMES);
```

- Retirer une clé de la session :
```php
$this->getSession()->remove(self::CART_GAMES);
```

- Vider entièrement la session :
```php
$this->getSession()->clear();
```



