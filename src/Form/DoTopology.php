<?php

/**

 * @file
 * Contains Drupal\netlab\Form\DoTopology.
 */
namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;
use Drupal\file\Entity\File;

class DoTopology extends FormBase {

  public function getFormId() {
    return 'do_topology_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    $form['topo_name']=array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#maxlength' => 25,
      '#required' => TRUE,
    );

    $form['description']=array(
      '#type' => 'textarea',
      '#title' => t('Description'),
      '#maxlength' => 1000,
      '#required' => TRUE,
    );

    $form['topo_schema']=array(
      '#type' => 'managed_file',
      '#title' => t('Topology image upload'),
      '#upload_location' => 'public://',
      '#required' => TRUE,

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
<<<<<<< HEAD
      '#title' => t('Ram resources [MB]'),
=======
      '#title' => t('Ram resources'),
>>>>>>> master
      '#maxlength' => 6,
      '#required' => TRUE,
    );

    $form['net_file']=array(
      '#type' => 'textarea',
      '#title' => t('Dynagen NetFile'),
      '#default_value' => t('==='),
    );
    $form['kvm_file']=array(
      '#type' => 'textarea',
      '#title' => t('KVM Configuration file'),
      '#default_value' => t('==='),
    );
    $form['kvm_file_label']=array(
      '#markup' => t('<p><i>*Read documentation for more informations</i></p>'),
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Create'),
        '#button_type' => 'primary',
    );
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //Tu to treba naprat
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $nowDate = date("Y-m-d H:i:s");
    $topo_name=$form_state->getValue('topo_name');
    $description=$form_state->getValue('description');
    $active=$form_state->getValue('active');
    $ram_resources=(int)$form_state->getValue('ram_resources');
    $net_file=$form_state->getValue('net_file');
    $kvm_file=$form_state->getValue('kvm_file');
    $topo_schema=reset($form_state->getValue('topo_schema'));

    print_r($topo_schema);
    $file = file_load($topo_schema);
    $file->status = FILE_STATUS_PERMANENT;
    $file->save();

    $console_count = (substr_count($net_file,"consPort") + substr_count($kvm_file,"consPort"));
    $vnc_count = substr_count($kvm_file,"vncPort");
<<<<<<< HEAD
    $virbr_count = 0;
    for($i=1;$i<20;$i++){
     if((substr_count($kvm_file,"virtNet".$i))==0){
       break;
     }else{
       $virbr_count++;
     }
    }
=======
    $virbr_count = substr_count($kvm_file,"virtNet");

>>>>>>> master

    db_insert('topology')
       ->fields(array(
         'topo_name' =>      $topo_name,
         'description' =>    $description,
         'topo_schema' =>    $topo_schema,
         'active' =>         (int)$active,
         'ram_resources' =>  (int)$ram_resources,
         'console_count'  => (int)$console_count,
         'net_file' =>       $net_file,
         'author' =>         \Drupal::currentUser()->id(),
         'created' =>        $nowDate,
         'kvm_file' =>       $kvm_file,
         'vnc_count' =>      $vnc_count,
         'virbr_count' =>    $virbr_count,
       ))->execute();


       drupal_set_message(t('New topology @topo_name has been added !',array(
           '@topo_name' =>  $topo_name,
         )
         )
       );
  }


}
