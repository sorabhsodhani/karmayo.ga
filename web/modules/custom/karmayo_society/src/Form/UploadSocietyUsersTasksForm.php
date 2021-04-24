<?php

namespace Drupal\karmayo_society\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\user\Entity\User;
use Drupal\karmayo_society\Entity\SocietyTasksEntity;
use Drupal\karmayo_society\KarmayoSocietyCoreService;

/**
 * Class UploadSocietyUsersTasksForm.
 */
class UploadSocietyUsersTasksForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'upload_society_users_tasks_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $sid = NULL) {
    $form['upload_tasks_csv'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload Tasks CSV'),
      '#weight' => '0',
      '#attributes' => ['class' => ['upload-tasks-file']],
      '#prefix' => '<div class="upload-tasks-file">',
      '#suffix' => '<span class="error-message"></span></div>',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      //'#upload_location' => 'private://society_tasks',
      '#ajax' => [
        'callback' => [$this, 'validateTasksFileMethod'],
        'progress' => ['type' => 'none', 'message' => NULL],
      ],
    ];
    $form['upload_users_csv'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload Users CSV'),
      '#weight' => '1',
      '#attributes' => ['class' => ['upload-users-file']],
      '#prefix' => '<div class="upload-users-file">',
      '#suffix' => '<span class="error-message"></span></div>',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      //'#upload_location' => 'private://society_users',
      '#ajax' => [
        'callback' => [$this, 'validateUsersFileMethod'],
        'progress' => ['type' => 'none', 'message' => NULL],
      ],
    ];
    $form['society_pick_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Pick a date'),
      '#weight' => '2',
      '#attributes' => array('min'=> \Drupal::service('date.formatter')->format(strtotime("+1 day"), 'custom', 'Y-m-d') ),
    ];
   $form['society_id'] = [
     '#type' => 'hidden',
     '#value' => $sid
   ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#weight' => '3'
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    //
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_tasks_file = $form_state->getValue('upload_tasks_csv', 0);
    if (isset($form_tasks_file[0]) && !empty($form_tasks_file[0])) {
      $tasks_file = File::load($form_tasks_file[0]);
      $tasks_file->setPermanent();
      $tasks_file->save();
      $destination = $tasks_file->get('uri')->value;
      $tasks_data_file = fopen($destination, "r");
      $tasks_data = [];
      $row = 0;
      while (!feof($tasks_data_file)) {
        $tasks_data[$row] = fgetcsv($tasks_data_file);
        $row++;
      }
      // Unset header row.
      unset($tasks_data[0]);
    }
    
    $form_users_file = $form_state->getValue('upload_users_csv', 0);
    if (isset($form_users_file[0]) && !empty($form_users_file[0])) {
      $users_file = File::load($form_users_file[0]);
      $users_file->setPermanent();
      $users_file->save();
      $destination = $users_file->get('uri')->value;
      $users_data_file = fopen($destination, "r");
      $users_data = [];
      $row = 0;
      while (!feof($users_data_file)) {
        $users_data[$row] = fgetcsv($users_data_file);
        $row++;
      }
      // Unset header row.
      unset($users_data[0]);
    }
    
    $sid = $form_state->getValue('society_id');
    $task_date = $form_state->getValue('society_pick_date');
    //kint($task_date);exit;
    
    // Save Users.
    $save_users = KarmayoSocietyCoreService::saveUsersOfSociety($users_data, $sid);
    if (is_bool($save_users) && $save_users) {
      \Drupal::messenger()->addMessage('Saved Users Successfully');
    }
    else if (is_array($save_users)){
      \Drupal::messenger()->addMessage('Uh oh! There is an issue with the below data you uploaded. Please check!');
      foreach ($save_users as $error_msg) {
        \Drupal::messenger()->addMessage($error_msg);
      }
    }
    
    // Save Tasks.
    $save_tasks = KarmayoSocietyCoreService::saveTasksOfSociety($tasks_data, $sid, $task_date);
    if (in_array(FALSE, $save_tasks, TRUE)) {
      \Drupal::messenger()->addMessage('Uh oh! There is an issue with the below data you uploaded. Please check!');
    }
    else {
      // Map tasks to users.
      $map_tasks_users = KarmayoSocietyCoreService::mapTasksToUsers($save_tasks, $sid, $task_date);
      if (in_array(FALSE, $map_tasks_users, TRUE)) {
        \Drupal::messenger()->addMessage('Uh oh! There is an issue with the below data you uploaded. Please check!');
      }
      else {
        \Drupal::messenger()->addMessage('Tasks are saved and mapped with respective users');
      }
    }
  }
  
  //public function validateTasksFileMethod(array &$form, FormStateInterface $form_state) {
  public function validateTasksFileMethod() {
    $response = new AjaxResponse();
    $validators = ['file_validate_extensions' => ['csv']];
    $file = file_save_upload('upload_tasks_csv', $validators, FALSE, 0);
    if (!$file) {
      $response->addCommand(new InvokeCommand('.upload-tasks-file', 'addClass', array('error')));
      $response->addCommand(new InvokeCommand('.upload-tasks-file .error-message', 'attr', ['value', 'This should be a CSV']));
      return $ajax_response;
    }

  // The rest of my submit function. 
  }
  
  public function validateUsersFileMethod() {
    $response = new AjaxResponse();
    $validators = ['file_validate_extensions' => ['csv']];
    $file = file_save_upload('upload_users_csv', $validators, FALSE, 0);
    if (!$file) {
      $response->addCommand(new InvokeCommand('.upload-users-file', 'addClass', array('error')));
      $response->addCommand(new InvokeCommand('.upload-users-file .error-message', 'attr', ['value', 'This should be a CSV']));
      return $ajax_response;
    }

  // The rest of my submit function. 
  }
}
