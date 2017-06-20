<?php

/**
 * @file
 * Contains Drupal\netlab\Form\EditReservation.
 */

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;

class EditReservation extends FormBase {


  public function getFormId() {
    return 'edit_reservation_form';
  }


  public function buildForm(array $form, FormStateInterface $form_state){
      $uid = \Drupal::currentUser()->id();
      $role = reset(\Drupal::currentUser()->getRoles(TRUE));

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
      foreach (NetlabStorage::list_start_reservations($uid,$role) as $record) {
       $rows[]=array(
         $record->reservation_id,
         $record->name,
         $record->term_date,
         $record->saved_until,
         $record->topo_name,
         $record->description,
       );
      }
      $header=array(t('Id'),t('Name'),t('Reservation date'),t('Saved until'),t('Name of topology'),t('Description'));
      $form['reservations'] = array(
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => t('No reservations'),
      );
<<<<<<< HEAD
     foreach(NetlabStorage::get_only_res_id($uid,$role) as $reser){
=======
     foreach(NetlabStorage::get_only_res_id($uid) as $reser){
>>>>>>> master
       $reservations[]=$reser->reservation_id;
     }
     $count=count($reservations);
     if($count!=0){
      $form['select'] = array(
        '#type' => 'select',
        '#title' => t('Select reservation to edit'),
        '#required' => TRUE,
        '#options' => $reservations,
      );
      $form['actions']['#type'] = 'actions';
    }
    $form['topology_select'] = array(
      '#type' => 'select',
      '#title' => t('Select topology'),
      '#options' => $topo,
      '#required' => TRUE,
      '#default_value' => $def_topo ,
    );

    $form['date_select'] = array(
      '#type' => 'select',
      '#title' => t('Select date & hour'),
      '#required' => TRUE,
      '#options' => $term,
      '#default_value' => $def_term,
    );
    $form['saved_until']=array(
        '#type' => 'select',
        '#title' => t('Select date & hour of deleting reservation'),
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
    $uid = \Drupal::currentUser()->id();
<<<<<<< HEAD
    $role = reset(\Drupal::currentUser()->getRoles(TRUE));
=======
>>>>>>> master
  foreach (NetlabStorage::topo_reserve() as $topo_record) {
        $topo[]=$topo_record->topo_name;
  }

  foreach (NetlabStorage::term_reserve() as $term_record) {
        $term[]=$term_record->term_date;
  }

<<<<<<< HEAD
  foreach(NetlabStorage::get_only_res_id($uid,$role) as $reser){
=======
  foreach(NetlabStorage::get_only_res_id($uid) as $reser){
>>>>>>> master
    $reservations[]=$reser->reservation_id;
  }
  $toponame=$topo[$form_state->getValue('topology_select')];
  $termname=$term[$form_state->getValue('date_select')];
  $reservation=$reservations[$form_state->getValue('select')];
  $saved_until=$term[$form_state->getValue('saved_until')];

  if($termname > $saved_until){
    drupal_set_message(t('Wrong dates ! '), 'error');
  }
  else{
     db_update('reservation')
       ->fields(array(
         'term_id' => NetlabStorage::get_term_id_by_term_date($termname),
         'topology_id' => NetlabStorage::get_topo_id_by_topo_name($toponame),
         'saved_until' => $saved_until,
       ))
       ->condition('reservation_id',$reservation)
       ->execute();

      drupal_set_message(t('You edit reservation @reservation to configure @topo on @date !',array(
      '@topo' => $toponame ,
      '@date' => $termname,
      '@reservation' => $reservation,
      )
      )
    );
  }
 }
}
