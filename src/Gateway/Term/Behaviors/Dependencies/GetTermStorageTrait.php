<?php

namespace Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies;

use Drupal\taxonomy\TermStorageInterface;

trait GetTermStorageTrait
{
  abstract protected function getTermStorage(): TermStorageInterface;
}
