<?php
namespace ShoppingCartBundle\EventListener;

use ShoppingCartBundle\Entity\BannedIP;
use ShoppingCartBundle\Repository\BannedIPRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class KernelListener
{
    private $bannedIPRepository;

    public function __construct(BannedIPRepository $bannedIPRepository)
    {
        $this->bannedIPRepository = $bannedIPRepository;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $bannedIps = $ids = array_map($transform = function(BannedIP $bannedIp) {
                return $bannedIp->getIp();
        }, $this->bannedIPRepository->findAll());

        if (in_array($event->getRequest()->getClientIp(), $bannedIps)) {
            $event->setResponse(new Response(
                'Your IP is banned!!! If you think there is a mistake please contact the administrator!',
                403));
        }
    }
}
