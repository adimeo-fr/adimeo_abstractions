<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition;

use Drupal\adimeo_abstractions\RouteMatch\GetNodeFromRouteMatchTrait;
use Drupal\adimeo_abstractions\RouteMatch\IsRouteNodeViewTrait;
use Drupal\Core\Routing\RouteMatchInterface;

trait SingleNodeViewApplyTrait {
  use GetNodeBundleTrait;
  use GetNodeFromRouteMatchTrait;
  use IsRouteNodeViewTrait;

  protected function isRouteMatchNodeViewOfNodeBundle(RouteMatchInterface $routeMatch) {
    return $this->isRouteMatchNodeViewOfBundle($routeMatch, $this->getNodeBundle());
  }
}
