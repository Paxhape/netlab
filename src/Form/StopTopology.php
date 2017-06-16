<?php

/**

 * @file
 * Contains Drupal\netlab\Form\StopTopology.
 */

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;

class StopTopology extends FormBase {

  public function getFormId() {
    return 'stop_topology_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
         $uid = \Drupal::currentUser()->id();
         $role = reset(\Drupal::currentUser()->getRoles(TRUE));

         foreach (NetlabStorage::list_run_reservations($role,$uid) as $record) {
          $rows[]=array(
            $record->running_topology_id,
            $record->started,
            $record->name,
            $record->topo_name,
            $record->description,
            $record->ram_resources,
          );
        }
         $header=array(t('Id'),t('Started'),t('By'),t('Name of topology'),t('Description'),t('Resources'));
         $form['running_topologies'] = array(
           '#type' => 'table',
           '#header' => $header,
           '#rows' => $rows,
           '#empty' => t('No running topologies'),
         );
        foreach(NetlabStorage::get_only_run_id($uid,$role) as $run){
          $runtop[]=$run->running_topology_id;
        }
        $count=count($runtop);
        if($count!=0){
         $form['runnin'] = array(
           '#type' => 'select',
           '#title' => t('Select topology to stop'),
           '#required' => TRUE,
           '#options' => $runtop,
         );
         $form['actions']['#type'] = 'actions';
         $form['actions']['submit'] = array(
             '#type' => 'submit',
             '#value' => t('Stop'),
             '#button_type' => 'primary',
         );
       }
         return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
    $uid = \Drupal::currentUser()->id();

    foreach(NetlabStorage::get_only_run_id($uid) as $run){
      $runtopid[]=$run->running_topology_id;
    }

   $run_top_id=$runtopid[$form_state->getValue('runnin')];

   foreach(NetlabStorage::get_pids($run_top_id) as $pids){
     $dynamips=$pids->pid_dynamips;
     $dynagen=$pids->pid_dynagen;
   }

   exec('kill '.$dynamips);
   exec('kill '.$dynagen);

   $delete = \Drupal::database()->delete('running_topology')
   ->condition('running_topology_id',$run_top_id)
   ->execute();
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message(t(' Topology stoped !'));
  }


}
