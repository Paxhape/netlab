<?php

/**
 * @file
 * Contains Drupal\netlab\Form\StartTopology.
 */

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class StartTopology extends FormBase {

  public function getFormId() {
    return 'start_topology_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    $form['actions']['#type'] = 'actions';
    $form['actions']['topo_start'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Start Topology'),
      '#button_type' => 'primary',
    );
    return $form;
  }

public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state){
    exec( ' virsh start debian8-test');
}

}
