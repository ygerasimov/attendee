<?php

namespace Drupal\attendee\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks if Drupal existed.
 *
 * @Constraint(
 *   id = "drupal_experience",
 *   label = @Translation("Drupal Experience", context = "Validation"),
 *   type = { "integer" }
 * )
 */
class DrupalExperience extends Constraint {

  public $message = 'Drupal was not around so many years!';

}
