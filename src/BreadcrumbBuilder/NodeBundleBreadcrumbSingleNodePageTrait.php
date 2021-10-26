<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\BreadcrumbBuilder\Applier\NodeBundleBreadcrumbBuilderSingleNodePageApplierTrait;
use Drupal\adimeo_abstractions\Constants\RouteMatchDefinitions;
use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

trait NodeBundleBreadcrumbSingleNodePageTrait {

  use HomeLinkTrait;
  use NodeBundleBreadcrumbBuilderSingleNodePageApplierTrait;

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



  abstract protected function getNodeBundle(): string;

  abstract protected function getListRoute(): string;

  abstract protected function getListLabel(): string;
}
