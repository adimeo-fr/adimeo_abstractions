<?php

namespace Drupal\adimeo_abstractions\Gateway\Node\Behaviors;

use Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies\GetGatewayLanguageDependencyTrait;
use Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies\GetNodeBundleDependencyTrait;
use Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies\GetNodeStorageDependencyTrait;
use Drupal\node\NodeInterface;

trait GetLastChangedPublishedNodeTrait
{
  use GetGatewayLanguageDependencyTrait;
  use GetNodeStorageDependencyTrait;
  use GetNodeBundleDependencyTrait;

  protected function getLastModifiedPublishedNid(): ?string {
    $query = $this->getNodeStorage()->getQuery();
    $query->condition('type', $this->getNodeBundle(), '=', $this->getLanguageService()->getCurrentLanguageId());
    $query->condition('status', NodeInterface::PUBLISHED);
    $query->sort('changed', 'DESC');
    $query->range(0, 1);

    $queryResult = $query->execute();

    if (!empty($queryResult)) {
      return reset($queryResult);
    }
    return NULL;
  }

  protected function getLastModifiedPublishedNode(): ?NodeInterface {
    $nid = $this->getLastModifiedPublishedNid();
    if ($nid) {
      /** @var NodeInterface $node */
      $node = $this->getNodeStorage()->load($nid);
      return $node;
    }

    return NULL;
  }


}
