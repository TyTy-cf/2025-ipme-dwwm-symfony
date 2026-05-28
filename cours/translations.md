
## Translations Symfony

Voir le fichier `config/packages/translation.yml` :

```yml
framework:
    default_locale: fr # <= changer en fr ici !
    translator:
        default_path: '%kernel.project_dir%/translations'
        providers:
```

- `default_locale` est la locale par défaut du site, par défaut avec Symfony elle est en "en"
- `default_path` définie où trouver les fichiers de chaînes de traduction dans le projet, par défaut c'est le dossier `translations`à la racine du projet Symfony

Les chaînes de traduction doivent respecter le format suivant : "nomFichier"."locale".yml

Par défaut Symfony en reconnaît deux :
- `messages.xx.yaml`
- `validators.xx.yaml`

Il est possible d'en créer de nouveaux, qui sont personnalisables, cependant il faut indiquer à Symfony lequel il va devoir utiliser.

Exemple de fichier de traductions :

```yml
game:
    form:
        title:
            edit: "Modification de %gameName%" # représente une chaine de traduction avec paramètre !
    properties:
        name: "Nom"
alert:
    game:
        new:
            success: "Le jeu a été créé !"
            danger: "Une erreur est survenue lors de la création du jeu"

```

### En Twig

On utilise le filtre Twig "trans" :

```html
{{ 'game.properties.name'|trans }}
```

```html
{{ ('game.form.title.' ~ mode)|trans({'%gameName%': game.name}) }}
```

### En PHP

On utilise l'objet `TranslatorInterface` : `use Symfony\Contracts\Translation\TranslatorInterface`

Puis on appelle la fonction `trans` :

```php
$type = 'success';
$this->addFlash($type, $this->translator->trans('alert.game.new.' . $type, [], 'admin'));
```
