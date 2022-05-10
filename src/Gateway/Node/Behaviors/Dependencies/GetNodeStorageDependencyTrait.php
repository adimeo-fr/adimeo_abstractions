<?php

namespace Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies;

use Drupal\node\NodeStorageInterface;

trait GetNodeStorageDependencyTrait
{
  abstract protected function getNodeStorage(): NodeStorageInterface;
}
