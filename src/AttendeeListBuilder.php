<?php

namespace Drupal\attendee;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class AttendeeListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $rows['name'] = $this->t('Name');
    $rows['seniority'] = $this->t('Seniority');
    $rows['bio'] = $this->t('Bio');

    return $rows + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['name'] = $entity->name->value;
    $row['seniority'] = $entity->seniority->value;
    $bio = $entity->bio->entity;
    $row['bio']['data'] = [
      '#type' => 'link',
      '#title' => $bio->label(),
      '#url' => $bio->toUrl(),
    ];

    return $row + parent:: buildRow($entity);
  }

}
