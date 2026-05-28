<?php

namespace App\DoctrineListener;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preUpdate, priority: 500)]
#[AsDoctrineListener(event: Events::prePersist, priority: 500)]
class CountryListener
{

    public function preUpdate(PreUpdateEventArgs $eventArgs): void {
        $this->generateUrlFlag($eventArgs->getObject());
    }

    public function prePersist(PrePersistEventArgs $eventArgs): void {
        $this->generateUrlFlag($eventArgs->getObject());
    }

    public function generateUrlFlag(object $object): void {
        if ($object instanceof Country) {
            $object->setUrlFlag('https://flagcdn.com/32x24/'.$object->getCode().'.png');
        }
    }

}
