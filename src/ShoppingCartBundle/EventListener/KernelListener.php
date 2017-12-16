<?php
namespace ShoppingCartBundle\EventListener;

use ShoppingCartBundle\Entity\BlackList;
use ShoppingCartBundle\Repository\BlackListRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class KernelListener
{
    private $blackListRepostiory;

    public function __construct(BlackListRepository $blackListRepository)
    {
        $this->blackListRepostiory = $blackListRepository;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $bannedIps = $ids = array_map($transform = function(BlackList $blacklist) {
                return $blacklist->getIp();
        }, $this->blackListRepostiory->findAll());

        if (in_array($event->getRequest()->getClientIp(), $bannedIps)) {
            $event->setResponse(new Response(
                'Your IP is banned!!! If you think there is a mistake please contact the administrator!',
                403));
        }
    }
}
