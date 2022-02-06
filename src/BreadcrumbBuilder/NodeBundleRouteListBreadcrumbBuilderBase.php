<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\SingleNodeViewApplyTrait;
use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\RouteListTrait;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;

abstract class NodeBundleRouteListBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  use SingleNodeViewApplyTrait;
  use RouteListTrait;

  public function applies(RouteMatchInterface $route_match) {
    return $this->isRouteMatchNodeViewOfNodeBundle($route_match) || $this->isRouteListRoute($route_match);
  }

  public function build(RouteMatchInterface $route_match) {
    return $this->isRouteListRoute($route_match)
      ? $this->buildListBreadcrumb($route_match)
      : $this->buildNodeViewBreadcrumb($route_match);
  }

}
