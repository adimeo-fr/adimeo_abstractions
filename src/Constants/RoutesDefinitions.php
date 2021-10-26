<?php

namespace Drupal\adimeo_abstractions\Constants;

interface RoutesDefinitions {

  public const FRONT = '<front>';

  public const NONE = '<none>';

  public const NODE_REVISION = 'entity.node.revision';

  public const NODE_CANONICAL = 'entity.node.canonical';

  public const NODE_VIEW_ROUTES = [
    RoutesDefinitions::NODE_CANONICAL,
    RoutesDefinitions::NODE_REVISION,
  ];

}
