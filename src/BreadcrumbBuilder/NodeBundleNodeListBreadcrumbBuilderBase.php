<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\GetNodeBundleTrait;
use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\HomeLinkTrait;
use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\NodeListTrait;
use Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition\SingleNodeViewApplyTrait;
use Drupal\adimeo_abstractions\RouteMatch\GetNodeFromRouteMatchTrait;
use Drupal\adimeo_abstractions\RouteMatch\IsRouteNodeViewTrait;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;

abstract class NodeBundleNodeListBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  use GetNodeFromRouteMatchTrait, HomeLinkTrait, IsRouteNodeViewTrait, GetNodeBundleTrait, NodeListTrait, SingleNodeViewApplyTrait;

  protected UrlGeneratorInterface $urlGenerator;

  public function __construct(UrlGeneratorInterface $urlGenerator) {
    $this->urlGenerator = $urlGenerator;
  }

  public function applies(RouteMatchInterface $route_match) {
    return $this->listNodeExists() &&
      (
        $this->isRouteMatchNodeViewOfBundle($route_match, $this->getNodeBundle())
        || $this->isRouteMatchNodeViewOfBundle($route_match, $this->getListNodeBundle())
      );
  }

  public function build(RouteMatchInterface $route_match) {
    $node = $this->getNodeFromRouteMatch($route_match);

    return $this->isCurrentNodeListNode($node)
      ? $this->buildListBreadcrumb($node)
      : $this->buildNodeBreadcrumb($node);
  }

  protected function listNodeExists(): bool {
    return (bool) $this->getListNode();
  }

}
