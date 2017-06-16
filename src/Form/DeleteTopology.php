<?php

/**

 * @file
 * Contains Drupal\netlab\Form\DeleteTopology.
 */
namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;


class DeleteTopology extends FormBase {

  public function getFormId() {
    return 'delete_topology_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
        foreach ($result=NetlabStorage::topo_id_load() as $toporecord){
            $rows[]=array(
                $toporecord->topology_id,
                $toporecord->topo_name,
                $toporecord->description,
                $toporecord->author,
                $toporecord->created,
                $toporecord->ram_resources,
                $toporecord->console_count,
            );
        }
        $header=array(t('ID'),t('Topology name'),t('Description'),t('Author'),t('Created'),t('Ram resources'),t('Console count'));
        $form['topologies']=array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => t('No topologies'),
        );
        foreach(NetlabStorage::get_only_topo_id() as $topor){
          $topologies[]=$topor->topology_id;
        }
        $count=count($topologies);
        if($count!=0){
        $form['select']=array(
            '#type' => 'select',
            '#title' => t('Select topology to delete'),
            '#required' => TRUE,
            '#options' => $topologies,
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Delete'),
            '#button_type' => 'primary',
        );
      }
     return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //Uvidime co spravi required
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    foreach(NetlabStorage::get_only_topo_id() as $topor){
      $topologies[]=$topor->topology_id;
    }

    $toDelete=$topologies[$form_state->getValue('select')];

    foreach(NetlabStorage::get_only_fid($toDelete) as $pomFid){
      $fid[]=$pomFid->topo_schema;
    }

    $imgDel = reset($fid);

    $delImage = \Drupal::database()->delete('file_managed')
                ->condition('fid',$imgDel)
                ->execute();

    $delete =  \Drupal::database()->delete('topology')
               ->condition('topology_id',$toDelete)
               ->execute();

    $reservations_delete = \Drupal::database()->delete('reservation')
                          ->condition('topology_id',$toDelete)
                          ->execute();

    drupal_set_message(t('Topology has been deleted  !'));
  }


}
