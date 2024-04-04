<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class BeforeRequestListener
{
    private EntityManager $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $filtro = $this->em->getFilters()
            ->enable('fortune_cookie_discontinued');

        $filtro->setParameter('discontinued', false);
    }
}