<?php

/**

 * @file
 * Contains Drupal\netlab\Form\EditTopology.
 */
<<<<<<< HEAD
 /*TODO*/
=======
>>>>>>> master

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;
use Drupal\file\Entity\File;

class EditTopology extends FormBase {

  public function getFormId() {
    return 'edit_topology_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    foreach ($result=NetlabStorage::topo_id_load() as $toporecord){
<<<<<<< HEAD
      $form_state->setCached(FALSE);
=======
>>>>>>> master
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
    $form['table_topologies']=array(
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
        '#title' => t('Select topology to edit'),
        '#options' => $topologies,
<<<<<<< HEAD
    );

    $form['load']=array(
        '#type' => 'submit',
        '#value' => t('Load values'),
        '#submit' => array('::load_values'),
=======
        '#ajax' => [
          'callback' => '::topoCallback',
          'wrapper' => 'topo_name'
        ],
    );

    $form_state->setCached(FALSE);

    $form['clear'] = array(
     '#name' => 'clear',
     '#type' => 'button',
     '#value' => t('Reset'),
     '#attributes' => array('onclick' => 'load_values('.$this->form.', '.$this->form_state.'); return true;'),
   );


    $form['topo_name']=array(
      '#type' => 'textfield',
      '#title' => t('Name of topology'),
      '#maxlength' => 25,
    );

    $form['description']=array(
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#maxlength' => 1000,
    );


    $form['topo_schema']=array(
      '#type' => 'managed_file',
      '#title' => t('Topology image upload'),
      '#upload_location' => 'public://',
    );

    $form['active_label']=array(
      '#type' => 'label',
      '#title' => t('Activeness'),
    );

    $form['active']=array(
      '#type' => 'checkbox',
      '#title' => t('Active'),
    );

    $form['ram_resources']=array(
      '#type' => 'textfield',
      '#title' => t('Ram resources'),
      '#maxlength' => 6,
    );

    $form['net_file']=array(
      '#type' => 'textarea',
      '#title' => t('Dynagen NetFile'),
    );
    $form['kvm_file']=array(
      '#type' => 'textarea',
      '#title' => t('KVM Configuration file'),
    );
    $form['kvm_file_label']=array(
      '#markup' => t('<p><i>*Read documentation for more informations</i></p>'),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save'),
        '#button_type' => 'primary',
>>>>>>> master
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
    $new_topology_id=$topologies[$form_state->getValue('select')];
    $nowDate = date("Y-m-d H:i:s");
    $topo_name=$form_state->getValue('topo_name');
    $description=$form_state->getValue('description');
    $topo_schema=$form_state->getValue('topo_schema');
    $active=(int)$form_state->getValue('active');
    $ram_resources=(int)$form_state->getValue('ram_resources');
    $console_count=(int)$form_state->getValue('console_count');
    $net_file=$form_state->getValue('net_file');
    $kvm_file=$form_state->getValue('kvm_file');
    $vnc_count=$form_state->getValue('vnc_count');
    $virbr_count=$form_state->getValue('virbr_count');
    $update = db_update('topology')
       ->fields(array(
         'topo_name' =>      $topo_name,
         'description' =>    $description,
         'topo_schema' =>    $topo_schema,
         'active' =>         $active,
         'ram_resources' =>  $ram_resources,
         'console_count'  => $console_count,
         'net_file' =>       $net_file,
         'editor' => \Drupal::currentUser()->id(),
         'edited' => $nowDate,
         'kvm_file' => $kvm_file,
         'vnc_count' => $vnc_count,
         'virbr_count' => $virbr_count,
       ))
       ->condition('topology_id', $new_topology_id,'=')
       ->execute();

       drupal_set_message(t('You saved @topology topology @topo_name, @description, @topo_scheme, @active, @ram_resources, @console_count, @net_file, @author @created   !',array(
           '@topology' => $new_topology_id,
           '@topo_name' =>      $form_state->getValue('topo_name'),
           '@description' =>    $form_state->getValue('description'),
           '@topo_scheme' =>    $form_state->getValue('topo_scheme'),
           '@active' =>         $form_state->getValue('active'),
           '@ram_resources' =>  $form_state->getValue('ram_resources'),
           '@console_count'  => $form_state->getValue('console_count'),
           '@net_file' =>       $form_state->getValue('net_file'),
           '@author' => \Drupal::currentUser()->id(),
           '@created' => $nowDate,

         )
         )
       );
  }

  public function load_values(array &$form, FormStateInterface $form_state){
<<<<<<< HEAD
    $topo_id = $form_state->getValue('select');
    foreach(NetlabStorage::editTopo($topo_id) as $rec){
      $topo_name=$rec->topo_name;
      $description=$rec->description;
      $topo_schema=$rec->topo_schema;
      $active=$rec->active;
      $ram_resources=$rec->ram_resources;
      $net_file=$rec->net_file;
      $kvm_file=$rec->kvm_file;
    }

        $form['topo_name']=array(
          '#type' => 'textfield',
          '#title' => t('Name of topology'),
          '#maxlength' => 25,
          '#default_value' => $topo_name,
        );

        $form['description']=array(
          '#type' => 'textfield',
          '#title' => t('Description'),
          '#maxlength' => 1000,
          '#default_value' => $description,
        );

        $form['topo_schema']=array(
          '#type' => 'managed_file',
          '#title' => t('Topology image upload'),
          '#upload_location' => 'public://',
        );

        $form['active_label']=array(
          '#type' => 'label',
          '#title' => t('Activeness'),
        );

        $form['active']=array(
          '#type' => 'checkbox',
          '#title' => t('Active'),
          '#default_value' => $active,
        );

        $form['ram_resources']=array(
          '#type' => 'textfield',
          '#title' => t('Ram resources'),
          '#maxlength' => 6,
          '#default_value' => $ram_resources,
        );

        $form['net_file']=array(
          '#type' => 'textarea',
          '#title' => t('Dynagen NetFile'),
          '#default_value' => $net_file,
        );
        $form['kvm_file']=array(
          '#type' => 'textarea',
          '#title' => t('KVM Configuration file'),
          '#default_value' => $kvm_file,
        );
        $form['kvm_file_label']=array(
          '#markup' => t('<p><i>*Read documentation for more informations</i></p>'),
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save'),
            '#button_type' => 'primary',
        );

        $form_state->setRebuild();


=======
    return drupal_set_message(t('You saved @topology topology @topo_name, @description, @topo_scheme, @active, @ram_resources, @console_count, @net_file, @author @created   !'), 'error');
>>>>>>> master
  }

}
