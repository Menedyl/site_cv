<?php

namespace App\EventListener;

use App\Entity\Picture;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class PictureListener
{
    private $path;


    public function __construct(string $path)
    {
        $this->path = $path;
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        if (!$args->getObject() instanceof Picture) {
            return;
        }

        /**
         * @var Picture $picture
         */
        $picture = $args->getObject();

        $picture->setAlt($picture->getName());
        $picture->uploadFile($this->path);
    }


    public function postRemove(LifecycleEventArgs $args)
    {
        if (!$args->getObject() instanceof Picture) {
            return;
        }

        /**
         * @var Picture $picture
         */
        $picture = $args->getObject();

        unlink($this->path . '/' . $picture->getUrl());
    }
}
