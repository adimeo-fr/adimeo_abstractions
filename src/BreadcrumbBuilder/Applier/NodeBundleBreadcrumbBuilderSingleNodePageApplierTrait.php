<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder\Applier;

use Drupal\adimeo_abstractions\RouteMatch\GetNodeFromRouteMatchTrait;
use Drupal\adimeo_abstractions\RouteMatch\IsRouteNodeViewTrait;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\Entity\Node;

trait NodeBundleBreadcrumbBuilderSingleNodePageApplierTrait {
  use GetNodeFromRouteMatchTrait;
  use IsRouteNodeViewTrait;

  protected function apply(RouteMatchInterface $routeMatch) {
    if (!$this->isRouteNodeView($routeMatch->getRouteName())) {
      return FALSE;
    }

    // Given the routes allowed to go so far, node param (Node object) will always exist.
    $node = $this->getNodeFromRouteMatch($routeMatch);
    return $this->isNodeOfTargetedBundle($node);
  }

  protected function isNodeOfTargetedBundle(Node $node) {
    return $node->bundle() === $this->getNodeBundle();
  }
}
