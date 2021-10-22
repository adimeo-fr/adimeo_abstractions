<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\Constants\RouteMatchDefinitions;
use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

trait NodeBundleBreadcrumbSingleNodePageTrait {

  use HomeLinkTrait;

  protected function isRouteNodeBundleView(RouteMatchInterface $routeMatch) {
    if (!$this->isRouteNodeView($routeMatch->getRouteName())) {
      return FALSE;
    }

    // Given the routes allowed to go so far, node param (Node object) will always exist.
    $node = $this->getNodeFromRouteMatch($routeMatch);
    return $this->isNodeOfTargetedBundle($node);
  }

  protected function isRouteNodeView(string $routeName) {
    return in_array($routeName, RoutesDefinitions::NODE_VIEW_ROUTES);
  }

  protected function buildNodeViewBreadcrumb(RouteMatchInterface $route_match): Breadcrumb {
    $node = $this->getNodeFromRouteMatch($route_match);
    $breadcrumb = new Breadcrumb();
    $breadcrumb->setLinks([
      $this->getHomeLink(),
      new Link(t($this->getListLabel()), Url::fromRoute($this->getListRoute())),
      new Link(t($node->getTitle()), Url::fromRoute(RoutesDefinitions::NONE)),
    ]);

    return $breadcrumb;
  }

  protected function getNodeFromRouteMatch(RouteMatchInterface $routeMatch): Node {
    return $routeMatch->getParameter(RouteMatchDefinitions::NODE_PARAMETER);
  }

  protected function isNodeOfTargetedBundle(Node $node) {
    return $node->bundle() === $this->getNodeBundle();
  }

  abstract protected function getNodeBundle(): string;

  abstract protected function getListRoute(): string;

  abstract protected function getListLabel(): string;
}
