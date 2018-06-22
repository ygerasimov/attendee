<?php

namespace Drupal\attendee;

use Drupal\attendee\Entity\Attendee;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AttendeeListBuilder extends EntityListBuilder {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_type, $storage);

    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $rows['name'] = $this->t('Name');
    $rows['seniority'] = $this->t('Seniority');
    $rows['bio'] = $this->t('Bio');
    $rows['changed'] = $this->t('Changed');

    return $rows + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['name'] = $entity->name->value;
    $row['seniority'] = Attendee::getSeniorityOptions()[$entity->seniority->value];
    $row['bio'] = '';
    if (!$entity->bio->isEmpty()) {
      $bio = $entity->bio->entity;
      $row['bio']['data'] = [
        '#type' => 'link',
        '#title' => $bio->label(),
        '#url' => $bio->toUrl(),
      ];
    }
    $row['changed'] = $this->dateFormatter->format($entity->getChangedTime(), 'short');

    return $row + parent:: buildRow($entity);
  }

}
