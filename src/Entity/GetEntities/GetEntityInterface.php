<?php

namespace Drupal\adimeo_abstractions\Entity\GetEntities;

use Drupal\Core\Entity\EntityInterface;

interface GetEntityInterface
{
  public function getEntity(): EntityInterface;
}
