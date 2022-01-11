<?php

namespace Drupal\adimeo_abstractions\RouteMatch;

use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;

trait IsRouteNodeViewTrait {

  protected function isRouteNodeView(string $routeName) {
    return in_array($routeName, RoutesDefinitions::NODE_VIEW_ROUTES);
  }

}
