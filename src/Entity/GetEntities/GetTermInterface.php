<?php

namespace Drupal\adimeo_abstractions\Entity\GetEntities;

use Drupal\taxonomy\TermInterface;

interface GetTermInterface
{
    public function getTerm(): TermInterface;
}
