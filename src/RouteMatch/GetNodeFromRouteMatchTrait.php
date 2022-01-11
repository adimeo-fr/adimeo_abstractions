<?php

namespace Drupal\adimeo_abstractions\RouteMatch;

use Drupal\adimeo_abstractions\Constants\RouteMatchDefinitions;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;

trait GetNodeFromRouteMatchTrait {
  protected function getNodeFromRouteMatch(RouteMatchInterface $routeMatch): NodeInterface {
    return $routeMatch->getParameter(RouteMatchDefinitions::NODE_PARAMETER);
  }
}
