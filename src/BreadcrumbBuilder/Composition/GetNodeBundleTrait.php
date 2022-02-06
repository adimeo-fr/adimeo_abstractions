<?php

namespace Drupal\adimeo_abstractions\BreadcrumbBuilder\Composition;

trait GetNodeBundleTrait {
  abstract protected function getNodeBundle(): string;
}
