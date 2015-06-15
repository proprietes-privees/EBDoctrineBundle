<?php

namespace EB\DoctrineBundle\Twig\Extension;

use EB\DoctrineBundle\Paginator\PaginatorHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class PaginatorExtension
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class PaginatorExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PaginatorHelper
     */
    private $paginatorHelper;

    /**
     * @var null|Request
     */
    private $request;

    /**
     * @param string          $name            Name
     * @param RouterInterface $router          Router
     * @param PaginatorHelper $paginatorHelper Paginator helper
     */
    public function __construct($name, RouterInterface $router, PaginatorHelper $paginatorHelper)
    {
        $this->name = $name;
        $this->router = $router;
        $this->paginatorHelper = $paginatorHelper;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $this->request = $event->getRequest();
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('order_by', [$this, 'getOrderBy'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Order
     *
     * @param string      $id           ID
     * @param string      $name         Name
     * @param null|string $defaultOrder Default order for this key
     *
     * @return string
     */
    public function getOrderBy($id, $name, $defaultOrder = null)
    {
        if (null === $this->request) {
            return $name;
        }

        $current = $id === $this->paginatorHelper->getOrderBy();
        $defaultOrder = $defaultOrder ? mb_strtoupper($defaultOrder) : null;
        $order = $current ? ('ASC' === $this->paginatorHelper->getOrderOrder() ? 'DESC' : 'ASC') : 'ASC';
        if (null === $this->paginatorHelper->getOrderBy()) {
            if (null !== $defaultOrder) {
                $current = true;
                $order = 'ASC' === $defaultOrder ? 'DESC' : 'ASC';
            }
        }

        $route = $this->request->attributes->get('_route');

        return sprintf(
            '<a class="%s %s" href="%s">%s%s</a>',
            $current ? 'order' : 'noorder',
            $order,
            $this->router->generate($route, array_merge($this->request->query->all(), [
                'offset' => $this->paginatorHelper->getOffset(),
                'limit' => $this->paginatorHelper->getLimit(),
                'order_by' => $id,
                'order_order' => $order,
            ], $this->request->attributes->get('_route_params'))),
            $name,
            $current ? ('ASC' === $order ? ' &uarr;' : ' &darr;') : ''
        );
    }
}
