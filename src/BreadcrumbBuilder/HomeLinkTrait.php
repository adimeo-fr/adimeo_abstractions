<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder;

use Drupal\adimeo_abstractions\Constants\RoutesDefinitions;
use Drupal\Core\Link;
use Drupal\Core\Url;

trait HomeLinkTrait {

  protected function getHomeLink(): Link {
    return new Link(t(self::getHomeLabel()), Url::fromRoute(RoutesDefinitions::FRONT));
  }

  public static function getHomeLabel(): string {
    return 'Home';
  }

}
