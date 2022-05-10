<?php

namespace Drupal\adimeo_abstractions\Entity\GetEntities;

use Drupal\paragraphs\ParagraphInterface;

interface GetParagraphInterface
{
  public function getParagraph(): ParagraphInterface;
}
