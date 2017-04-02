<?php

/**
 * @file
 * Contains Drupal\netlab\Form\StopTopology.
 */

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class StopTopology extends FormBase {

  public function getFormId() {
    return 'stop_topology_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    $form['topo_stop'] = array(
      '#type' => ''
    );
  }

public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state){
    
}

}
