<?php

namespace Drupal\adimeo_abstractions\RouteMatch;

use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Routing\RouteMatchInterface;

trait IsRouteNodeViewTrait {

  protected function isRouteNodeView(string $routeName) {
    return in_array($routeName, RoutesDefinitions::NODE_VIEW_ROUTES);
  }

  protected function isRouteMatchNodeViewOfBundle(RouteMatchInterface $routeMatch, string $bundle) {
    if (!$this->isRouteNodeView($routeMatch->getRouteName())) {
      return FALSE;
    }

    // Given the routes allowed to go so far, node param (Node object) will always exist.
    $node = $this->getNodeFromRouteMatch($routeMatch);
    return $node->bundle() === $bundle;
  }
}
