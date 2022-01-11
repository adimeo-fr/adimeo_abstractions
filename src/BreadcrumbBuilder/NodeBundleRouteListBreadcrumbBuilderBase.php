<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;

abstract class NodeBundleRouteListBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  use NodeBundleBreadcrumbSingleNodePageTrait {
    apply as applyNodeBundleSinglePage;
  }

  use NodeBundleBreadcrumbListNodePageTrait;

  public function applies(RouteMatchInterface $route_match) {
    return $this->applyNodeBundleSinglePage($route_match) || $this->isRouteNodeTypeList($route_match);
  }

  public function build(RouteMatchInterface $route_match) {
    return $this->isRouteNodeTypeList($route_match)
      ? $this->buildListBreadcrumb($route_match)
      : $this->buildNodeViewBreadcrumb($route_match);
  }

}
