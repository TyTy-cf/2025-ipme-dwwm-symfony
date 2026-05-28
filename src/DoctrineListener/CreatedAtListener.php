<?php

namespace App\DoctrineListener;

use App\Interfaces\CreatedAtInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist, priority: 500)]
class CreatedAtListener
{

    public function prePersist(PrePersistEventArgs $eventArgs): void {
        $object = $eventArgs->getObject();
        if ($object instanceof CreatedAtInterface) {
            $object->setCreatedAt(new DateTimeImmutable());
        }
    }

}
