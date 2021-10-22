<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

abstract class NodeBundleBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  const NODE_PARAMETER = 'node';

  const NODE_CANONICAL_ROUTE = 'entity.node.canonical';

  const NODE_REVISION_ROUTE = 'entity.node.revision';

  const FRONT_ROUTE = '<front>';

  const NONE_ROUTE = '<none>';

  const HOME_LABEL = 'Home';

  const NODE_VIEW_ROUTES = [
    self::NODE_CANONICAL_ROUTE,
    self::NODE_REVISION_ROUTE,
  ];

  public function applies(RouteMatchInterface $route_match) {
    return $this->isRouteNodeTypeView($route_match) || $this->isRouteNodeTypeList($route_match);
  }

  public function build(RouteMatchInterface $route_match) {
    return $this->buildNodeViewBreadcrumb($route_match);
//    return $this->isRouteNodeTypeList($route_match)
//      ? $this->buildListBreadcrumb($route_match)
//      : $this->buildNodeViewBreadcrumb($route_match);
  }

  protected function isRouteNodeTypeView(RouteMatchInterface $routeMatch) {
    if (!$this->isRouteNodeView($routeMatch->getRouteName())) {
      return FALSE;
    }

    // Given the routes allowed to go so far, node param (Node object) will always exist.
    $node = $this->getNodeFromRouteMatch($routeMatch);
    return $this->isNodeOfTargetedBundle($node);
  }

  protected function isRouteNodeView(string $routeName) {
    return in_array($routeName, self::NODE_VIEW_ROUTES);
  }

  protected function isRouteNodeTypeList(RouteMatchInterface $routeMatch): bool {
    return $this->isRouteList($routeMatch->getRouteName());
  }

  protected function isRouteList(string $routeName): bool {
    return $routeName === $this->getListRoute();
  }

  protected function isNodeOfTargetedBundle(Node $node) {
    return $node->bundle() === $this->getNodeBundle();
  }

  protected function buildListBreadcrumb(RouteMatchInterface $route_match): Breadcrumb {
    return new Breadcrumb();
  }

  protected function buildNodeViewBreadcrumb(RouteMatchInterface $route_match): Breadcrumb {
    $node = $this->getNodeFromRouteMatch($route_match);
    $breadcrumb = new Breadcrumb();
    $breadcrumb->setLinks([
      new Link(t(self::HOME_LABEL), Url::fromRoute(self::FRONT_ROUTE)),
      new Link(t($this->getListLabel()), Url::fromRoute($this->getListRoute())),
      new Link(t($node->getTitle()), Url::fromRoute(self::NONE_ROUTE)),
    ]);

    return $breadcrumb;
  }

  protected function getNodeFromRouteMatch(RouteMatchInterface $routeMatch): Node {
    return $routeMatch->getParameter(self::NODE_PARAMETER);
  }

  abstract protected function getNodeBundle(): string;

  abstract protected function getListRoute(): string;

  abstract protected function getListLabel(): string;

}
