<?php

namespace Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies;

use Drupal\adimeo_tools\Service\LanguageService;

trait GetGatewayLanguageDependencyTrait
{
  abstract protected function getLanguageService(): LanguageService;
}
