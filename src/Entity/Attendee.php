<?php

namespace Drupal\attendee\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Attendee entity type.
 *
 * @ContentEntityType(
 *   id = "attendee",
 *   label = @Translation("Attendee"),
 *   handlers = {
 *     "form" = {
 *       "default" = "Drupal\Core\Entity\ContentEntityForm",
 *       "add" = "Drupal\Core\Entity\ContentEntityForm",
 *       "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "attendee",
 *   data_table = "attendee_field_data",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/attendee/{attendee}",
 *     "edit-form" = "/attendee/{attendee}/edit",
 *     "add-form" = "/attendee/{attendee}/add",
 *     "delete-form" = "/attendee/{attendee}/delete",
 *     "collection" = "/attendee/list",
 *   },
 *   admin_permission = "administer attendees"
 * )
 */
class Attendee extends ContentEntityBase {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('First and last name of attendee.'))
      ->setRequired(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ]);

    $fields['seniority'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Seniority'))
      ->setDescription(t('How fluent developer you are.'))
      ->setRequired(TRUE)
      ->setSetting('allowed_values', static::getSeniorityOptions())
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 1,
      ]);

    $fields['bio'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bio'))
      ->setDescription(t('Attendee Bio page'))
      ->setSetting('target_type', 'node')
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'placeholder' => t('Select if you created dedicated Bio page'),
        ],
        'weight' => 15,
      ]);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the node was last edited.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    $fields['experience'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Experience'))
      ->setDescription(t('How many years you are using Drupal?'))
      ->addConstraint('drupal_experience')
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 2,
      ]);

    return $fields;

  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->name->value;
  }

  /**
   * {@inheritdoc}
   */
  public function toUrl($rel = 'canonical', array $options = []) {
    if ($rel == 'collection') {
      $uri = new Url('view.attendees.page_1');
      // Pass the entity data through as options, so that alter functions do not
      // need to look up this entity again.
      $uri
        ->setOption('entity_type', $this->getEntityTypeId())
        ->setOption('entity', $this);
      return $uri;
    }

    return parent::toUrl($rel, $options);
  }

  /**
   * Options for Seniority levels.
   */
  static function getSeniorityOptions() {
    // Do queries to database.
    return [
      'junior' => t('Junior'),
      'middle' => t('Middle'),
      'senior' => t('Senior'),
      'lead' => t('Team Lead'),
      'cto' => t('CTO'),
    ];
  }

}
