<?php

/**
 * @file
 * Contains Drupal\netlab\Form\DoReservation.
 */
//chyba
namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;
use Drupal\netlab\Controller\NetlabController;

class DoReservation extends FormBase {


  public function getFormId() {
    return 'do_reservation_form';
  }


  public function buildForm(array $form, FormStateInterface $form_state){

      $form=NetlabController::list_topologies();

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

  foreach (NetlabStorage::topo_reserve() as $topo_record) {
        $topo[]=$topo_record->topo_name;
  }

  foreach (NetlabStorage::term_reserve() as $term_record) {
        $term[]=$term_record->term_date;
  }
  $toponame=$topo[$form_state->getValue('topology_select')];
  $termname=$term[$form_state->getValue('date_select')];
  $saved_until=$term[$form_state->getValue('saved_until')];

  if($termname > $saved_until){
    drupal_set_message(t('Wrong dates ! '), 'error');
  }
  else{
  $term_date=NetlabStorage::get_term_id_by_term_date($termname);
  $nowDate = date("Y-m-d H:i:s");
     db_insert('reservation')
       ->fields(array(
         'user_id' => \Drupal::currentUser()->id(),
         'term_id' => $term_date,
         'topology_id' => NetlabStorage::get_topo_id_by_topo_name($toponame),
         'created' => $nowDate,
         'saved_until' => $saved_until,
       ))->execute();
  /*
  foreach(NetlabStorage::getFreeCapacity($term_date) as $gfc){
     $free_capacity[]=$gfc->free_capacity;
  }
  foreach(NetlabStorage::getRamRecources($toponame) as $grr){
    $topo_ram[]=$grr->ram_resources;
  }

<<<<<<< HEAD
  foreach(NetlabStorage::getFreeCapacity($term_date) as $gfc){
     $free_capacity[]=$gfc->free_capacity;
  }

  foreach(NetlabStorage::getRamRecources($toponame) as $grr){
    $topo_ram[]=$grr->ram_resources;
  }

  $new_free_capacity = reset($free_capacity) - reset($topo_ram);
      db_update('term')
          ->fields(array(
            'free_capacity' => $new_free_capacity,
          ))
          ->condition('term_id', $term_date)
          ->execute();

=======
  $new_free_capacity = reset($free_capacity) - reset($topo_ram);
       $update = db_update('term')
          ->fields(array(
            'free_capacity' => $new_free_capacity,
          ))
          ->condition('term_date', $term_date)
          ->execute();
*/
>>>>>>> master
      drupal_set_message(t('You saved topology @topo for @date !',array(
      '@topo' => $new_free_capacity ,
      '@date' => reset($free_capacity),
      )
      )
    );
  }
}

}
