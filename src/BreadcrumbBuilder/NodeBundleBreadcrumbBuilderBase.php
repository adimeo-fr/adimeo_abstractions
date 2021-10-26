<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;

abstract class NodeBundleBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  use NodeBundleBreadcrumbSingleNodePageTrait;
  use NodeBundleBreadcrumbListNodePageTrait;

  public function applies(RouteMatchInterface $route_match) {
    return $this->isRouteNodeBundleView($route_match) || $this->isRouteNodeTypeList($route_match);
  }

  public function build(RouteMatchInterface $route_match) {
    return $this->isRouteNodeTypeList($route_match)
      ? $this->buildListBreadcrumb($route_match)
      : $this->buildNodeViewBreadcrumb($route_match);
  }

}
