<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition;

use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;

trait NodeListTrait {

  abstract protected function getListNode(): ?NodeInterface;

  protected function getListNodeBundle(): string {
    return $this->getListNode()->bundle();
  }

  protected function isCurrentNodeListNode(NodeInterface $node) {
    return $node->id() === $this->getListNode()->id();
  }

  protected function getListUrl() {
    return new Url(RoutesDefinitions::NODE_CANONICAL, [
      'node' => $this->getListNode()
        ->id(),
    ]);
  }

  protected function getListLink() {
    return new Link(t($this->getListNode()->getTitle()), $this->getListUrl());
  }

  protected function buildListBreadcrumb(\Drupal\node\NodeInterface $node): Breadcrumb {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->setLinks([
      $this->getHomeLink(),
      new Link(t($node->getTitle()), Url::fromRoute(RoutesDefinitions::NONE)),
    ]);

    return $breadcrumb;
  }

  protected function buildNodeBreadcrumb(\Drupal\node\NodeInterface $node): Breadcrumb {
    $breadcrumb = new Breadcrumb();

    $breadcrumb->setLinks([
      $this->getHomeLink(),
      $this->getListLink(),
      new Link(t($node->getTitle()), Url::fromRoute(RoutesDefinitions::NONE)),
    ]);

    return $breadcrumb;
  }
}
