<?php

/**
 * @file
 * Contains Drupal\netlab\Form\DoReservation.
 */

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;


class DoReservation extends FormBase {

  public function getFormId() {
    return 'do_reservation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

        foreach ($topo_result=NetlabStorage::topo_reserve() as $topo_record) {
          $topo[]=$topo_record->topo_name;
        }

        foreach ($term_result=NetlabStorage::term_reserve() as $term_record) {
          $term[]=$term_record->term_date;
        }
      echo $final_topo = array_values($topo);
    $form['topology_select'] = array(
      '#type' => 'select',
      '#title' => t('Select topology'),
      '#options' => $final_topo,
      '#required' => TRUE,
    );

    $form['date_select'] = array(
      '#type' => 'select',
      '#title' => t('Select date & hour'),
      '#required' => TRUE,
      '#options' => $term,
    );
   $form['actions']['#type'] = 'actions';
   $form['actions']['submit'] = array(
       '#type' => 'submit',
       '#value' => t('Reserve'),
       '#button_type' => 'primary',
   );
  return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //Uvidime co spravi required
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
      $key_term = $form_state['values']['date_select'];
      $term_date = $form['date_select']['#options'][$key_term];
      $topo_term = $form_state['values']['topology_select'];
      $topo_name = $form['topology_select']['#options'][$key_term];
      drupal_set_message($topo_name);
        db_insert('reservation')
      ->fields(array(
          'user_id' => \Drupal::currentUser()->id(),
          'term_id'=> NetlabStorage::get_term_id_by_term_date($form_state->getValue('date_select')),
          'topology_id' => NetlabStorage::get_topo_id_by_topo_name($form_state->getValue('topology_select')),
      ))->execute();
    drupal_set_message(t('Your reservation has been sucessfully saved !'),'status');
  }

}
