<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;

trait NodeBundleBreadcrumbListNodePageTrait {

  use HomeLinkTrait;

  protected function isRouteNodeTypeList(RouteMatchInterface $routeMatch): bool {
    return $this->isRouteList($routeMatch->getRouteName());
  }

  protected function isRouteList(string $routeName): bool {
    return $routeName === $this->getListRoute();
  }

  protected function buildListBreadcrumb(RouteMatchInterface $route_match): Breadcrumb {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->setLinks([
      $this->getHomeLink(),
      new Link(t($this->getListLabel()), Url::fromRoute(RoutesDefinitions::NONE)),
    ]);
    return $breadcrumb;
  }

  abstract protected function getListRoute(): string;

  abstract protected function getListLabel(): string;
}
