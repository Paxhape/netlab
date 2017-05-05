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
      $count=count($topo);
      if($count==0){
         drupal_set_message(t(' No topology ! '));
      }
      foreach ($term_result=NetlabStorage::term_reserve() as $term_record) {
        $term[]=$term_record->term_date;
      }
      $count=count($term);
      if($count==0){
        drupal_set_message(t(' No terms ! '));
      }
    $form['topology_select'] = array(
      '#type' => 'select',
      '#title' => t('Select topology'),
      '#options' => $topo,
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
    }

  public function submitForm(array &$form, FormStateInterface $form_state) {

  foreach (NetlabStorage::topo_reserve() as $topo_record) {
        $topo[]=$topo_record->topo_name;
  }

  foreach (NetlabStorage::term_reserve() as $term_record) {
        $term[]=$term_record->term_date;
  }
  $toponame=$topo[$form_state->getValue('topology_select')];
  $termname=$term[$form_state->getValue('date_select')];
  $nowDate = date("Y-m-d H:i:s");
     db_insert('reservation')
       ->fields(array(
         'user_id' => \Drupal::currentUser()->id(),
         'term_id' => NetlabStorage::get_term_id_by_term_date($termname),
         'topology_id' => NetlabStorage::get_topo_id_by_topo_name($toponame),
         'created' => $nowDate,
       ))->execute();

      drupal_set_message(t('You saved topology @topo for @date !',array(
      '@topo' => $toponame ,
      '@date' => $termname,
      )
      )
    );
  }

}
