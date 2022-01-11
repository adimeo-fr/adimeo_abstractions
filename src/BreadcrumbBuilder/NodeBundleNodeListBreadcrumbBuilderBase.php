<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;

abstract class NodeBundleNodeListBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  protected $urlGenerator;

  use NodeBundleBreadcrumbSingleNodePageTrait {
    apply as applyNodeBundleSinglePage;
  }

  public function __construct(UrlGeneratorInterface $urlGenerator) {
    $this->urlGenerator = $urlGenerator;
  }

  public function applies(RouteMatchInterface $route_match) {
    return $this->applyNodeBundleSinglePage($route_match) || $this->applyNodeListTypeSinglePage($route_match);
  }

  public function build(RouteMatchInterface $route_match) {
    $node = $this->getNodeFromRouteMatch($route_match);

    $breadcrumb = new Breadcrumb();

    if ($this->isCurrentNodeListNode($node)) {
      $breadcrumb->setLinks([
        $this->getHomeLink(),
        new Link(t($node->getTitle()), Url::fromRoute(RoutesDefinitions::NONE)),
      ]);
    } else {
      $breadcrumb->setLinks([
        $this->getHomeLink(),
        $this->getListLink(),
        new Link(t($node->getTitle()), Url::fromRoute(RoutesDefinitions::NONE)),
      ]);
    }


    return $breadcrumb;
  }

  protected function applyNodeListTypeSinglePage(RouteMatchInterface $routeMatch) {
    if (!$this->isRouteNodeView($routeMatch->getRouteName())) {
      return FALSE;
    }

    // Given the routes allowed to go so far, node param (Node object) will always exist.
    $node = $this->getNodeFromRouteMatch($routeMatch);
    return $this->isNodeOfListTargetedBundle($node);
  }

  protected function isNodeOfListTargetedBundle(NodeInterface $node): bool {
    return $node->bundle() === $this->getListNodeBundle();
  }

  protected function getListLabel() {
    return $this->getlistNode()->getTitle();
  }

  protected function getListUrl() {
    return new Url(RoutesDefinitions::NODE_CANONICAL, ['node' => $this->getListNode()->id()]);
  }

  protected function getListNodeBundle(): string {
    return $this->getListNode()->bundle();
  }

  abstract protected function getListNode(): NodeInterface;

  protected function getListLink() {
    return new Link(t($this->getListNode()->getTitle()), $this->getListUrl());
  }

  protected function isCurrentNodeListNode(NodeInterface $node) {
    return $node->id() === $this->getListNode()->id();
  }

}
