<?php

namespace Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies;

trait GetNodeBundleDependencyTrait
{
  abstract protected function getNodeBundle(): string;
}
