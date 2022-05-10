<?php

namespace Drupal\adimeo_abstractions\Gateway\Node\Behaviors;

use Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies\GetTermStorageTrait;
use Drupal\adimeo_abstractions\Gateway\Node\Behaviors\Dependencies\GetVocabularyIdTrait;
use Drupal\taxonomy\TermInterface;

trait GetAllTermsByWeightTrait
{

  use GetTermStorageTrait;
  use GetVocabularyIdTrait;

  /**
   * @return int[]
   */
  public function fetchTermsNidsByWeight(): array {
    return $this->getTermStorage()
      ->getQuery()
      ->condition('vid', $this->getVocabularyId())
      ->sort('weight', 'ASC')
      ->execute();
  }

  /**
   * @return TermInterface[]
   */
  public function fetchTermsByWeight(): array {
    $nids = $this->fetchTermsNidsByWeight();
    return $this->getTermStorage()->loadMultiple($nids);
  }
}
