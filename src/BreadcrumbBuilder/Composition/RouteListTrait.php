<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition;

use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\adimeo_abstractions\RouteMatch\GetNodeFromRouteMatchTrait;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use function t;

trait RouteListTrait {

  use HomeLinkTrait;
  use GetNodeFromRouteMatchTrait;

  protected function isRouteListRoute(RouteMatchInterface $routeMatch): bool {
    return $this->getListRoute() == $routeMatch->getRouteName();
  }

  protected function buildListBreadcrumb(RouteMatchInterface $route_match): Breadcrumb {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url']);
    $breadcrumb->setLinks([
      $this->getHomeLink(),
      new Link(t($this->getListLabel()), Url::fromRoute(RoutesDefinitions::NONE)),
    ]);
    return $breadcrumb;
  }

  protected function buildNodeViewBreadcrumb(RouteMatchInterface $route_match): Breadcrumb {
    $node = $this->getNodeFromRouteMatch($route_match);
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url']);
    $breadcrumb->setLinks([
      $this->getHomeLink(),
      new Link(t($this->getListLabel()), Url::fromRoute($this->getListRoute())),
      new Link(t($node->getTitle()), Url::fromRoute(RoutesDefinitions::NONE)),
    ]);

    return $breadcrumb;
  }

  abstract protected function getListRoute(): string;

  abstract protected function getListLabel(): string;
}
