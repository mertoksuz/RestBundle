<?php

namespace Nedra\RestBundle\Routing;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class ModularRouter
 * @package Nedra\RestBundle\Routing
 */
final class ModularRouter implements ModularRouterInterface
{
    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var RequestContext
     */
    private $requestContext;

    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct()
    {
        $this->routeCollection = new RouteCollection;
    }

    /**
     * @param RouteCollectionProviderInterface $routeCollectionProvider
     */
    public function addRouteCollectionProvider(RouteCollectionProviderInterface $routeCollectionProvider)
    {
        $this->routeCollection->addCollection($routeCollectionProvider->getRouteCollection());
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }

    /**
     * @param RequestContext $requestContext
     */
    public function setContext(RequestContext $requestContext)
    {
        $this->requestContext = $requestContext;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param int $referenceType
     * @return string
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        return $this->getUrlGenerator()
            ->generate($name, $parameters, $referenceType);
    }

    /**
     * @param string $pathinfo
     * @return array[]
     */
    public function match($pathinfo)
    {
        return $this->getUrlMatcher()
            ->match($pathinfo);
    }

    public function getContext()
    {
        // this method is never used
        return '...';
    }

    /**
     * @return UrlGenerator|UrlGeneratorInterface
     */
    private function getUrlGenerator()
    {
        if ($this->urlGenerator) {
            return $this->urlGenerator;
        }

        return $this->urlGenerator = new UrlGenerator($this->getRouteCollection(), $this->requestContext);
    }

    /**
     * @return UrlMatcher|UrlMatcherInterface
     */
    private function getUrlMatcher()
    {
        if ($this->urlMatcher) {
            return $this->urlMatcher;
        }

        return $this->urlMatcher = new UrlMatcher($this->getRouteCollection(), $this->requestContext);
    }
}
