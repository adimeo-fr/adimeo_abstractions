<?php

namespace Drupal\adimeo_abstractions\Service;

use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\GetNodeBundleTrait;
use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\HomeLinkTrait;
use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\SingleNodeViewApplyTrait;
use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\adimeo_abstractions\RouteMatch\GetNodeFromRouteMatchTrait;
use Drupal\adimeo_abstractions\RouteMatch\IsRouteNodeViewTrait;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;

abstract class NodeBundleBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  use GetNodeFromRouteMatchTrait, HomeLinkTrait, IsRouteNodeViewTrait, GetNodeBundleTrait, SingleNodeViewApplyTrait;

  protected UrlGeneratorInterface $urlGenerator;

  public function __construct(UrlGeneratorInterface $urlGenerator) {
    $this->urlGenerator = $urlGenerator;
  }

  public function applies(RouteMatchInterface $route_match): bool {
    return $this->isRouteMatchNodeViewOfBundle($route_match, $this->getNodeBundle());
  }

  public function build(RouteMatchInterface $route_match): Breadcrumb {
    $node = $this->getNodeFromRouteMatch($route_match);
    return $this->buildSingleNodeBreadcrumb($node);
  }

  protected function buildSingleNodeBreadcrumb(NodeInterface $node): Breadcrumb {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url']);
    $breadcrumb->setLinks([
      $this->getHomeLink(),
      new Link(t($node->getTitle()), Url::fromRoute(RoutesDefinitions::NONE)),
    ]);

    return $breadcrumb;
  }

}
