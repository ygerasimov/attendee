<?php

namespace Drupal\attendee\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the DrupalExperience constraint.
 */
class DrupalExperienceValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    foreach ($items as $item) {
      $dateDiff = (int) format_date(REQUEST_TIME, 'custom', 'Y') - 2000;
      if ($item->value > $dateDiff) {
        $this->context->addViolation($constraint->message);
      }
    }
  }

}
