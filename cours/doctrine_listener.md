
# Doctrine listener


## Les listeners


Les listeners sont des classes qui permettent de déclencher des actions à des moments précis dans le cycle de vie d'une entité.

Pour créer un listener, il faut créer une classe et l'annoter avec `#[AsDoctrineListener]`, à l'intérieur duquel on précise quels événements on veut écouter.

Par exemple, pour écouter l'événement `preUpdate` :

```php
#[AsDoctrineListener(event: Events::preUpdate, priority: 500)]
class MyListener
{

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof SlugInterface) {
            // Slug logic there
        }
    }
}
```

- priority : permet de définir l'ordre d'exécution des listeners (plus le nombre est élevé, plus le listener sera exécuté en premier)


## Les événements utilisables


Les événements sont des constantes de la classe `Doctrine\ORM\Events` :

- `prePersist` : avant l'insertion en base de données
- `postPersist` : après l'insertion en base de données
- `preUpdate` : avant la mise à jour en base de données
- `postUpdate` : après la mise à jour en base de données
- `preRemove` : avant la suppression en base de données
- `postRemove` : après la suppression en base de données
- `postLoad` : après le chargement d'une entité
- `preFlush` : avant le flush
- `onFlush` : pendant le flush
- `postFlush` : après le flush
- `loadClassMetadata` : après le chargement des métadonnées d'une entité


# Exemple avec plusieurs événements


```php
#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]
class MyListener
{

    public function prePersist(LifecycleEvent $argts): void {
    
    }
 
```
