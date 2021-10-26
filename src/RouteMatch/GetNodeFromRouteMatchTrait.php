<?php

namespace Drupal\adimeo_abstractions\RouteMatch;

use Drupal\adimeo_abstractions\Constants\RouteMatchDefinitions;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\Entity\Node;

trait GetNodeFromRouteMatchTrait {
  protected function getNodeFromRouteMatch(RouteMatchInterface $routeMatch): Node {
    return $routeMatch->getParameter(RouteMatchDefinitions::NODE_PARAMETER);
  }
}
