<?php

namespace Drupal\forcontu_entities\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Builds the form to delete Section entities.
 */
class SectionDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete @name?', ['@name' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.forcontu_entities_section.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();
    $this->messenger()->addMessage(
      $this->t('content @type: deleted @label.', [
        '@type' => $this->entity->bundle(),
        '@label' => $this->entity->label(),
      ])
    );
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
