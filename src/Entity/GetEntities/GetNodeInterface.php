<?php

namespace Drupal\adimeo_abstractions\Entity\GetEntities;

use Drupal\node\NodeInterface;

interface GetNodeInterface
{
  public function getNode(): NodeInterface;
}
