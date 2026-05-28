<?php

namespace App\Slugify;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preUpdate, priority: 500)]
#[AsDoctrineListener(event: Events::prePersist, priority: 500)]
readonly class SlugifyEventSubscriber
{

    public function __construct(private SlugService $slugService)
    {
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void {
        $this->handle($eventArgs->getObject());
    }

    public function prePersist(PrePersistEventArgs $eventArgs): void {
        $this->handle($eventArgs->getObject());
    }

    private function handle(object $object): void {
        if ($object instanceof SlugInterface) {
            $object->setSlug($this->slugService->slugify($object->getFields()));
        }
    }

}
