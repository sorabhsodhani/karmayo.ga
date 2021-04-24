<?php

namespace Drupal\karmayo_pledge\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Drupal\karmayo_pledge\Entity\KarmayoPledgeEntity;
use Drupal\karmayo_pledge\KarmayoCoreService;

/**
 * Class KarmayoUserPickForm.
 */
class KarmayoUserPickForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'karmayo_user_pick_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $options = [];
    $goal_nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
      'type' => 'karma_activities',
    ]);
    $search_params['filter_start_date'] = date('Y-m-01 00:00:00');
    $search_params['filter_end_date'] = date('Y-m-t 00:00:00');
    $user = \Drupal::currentUser();
    $data = KarmayoCoreService::getUsersTransaction($search_params, $user->id());
    if (!empty($data) && isset($data[$user->id()]) && !empty($data[$user->id()]['points'])) {
      $points_in_month = $data[$user->id()]['points'];
      if ($points_in_month > 150) {
        $max = 5;
      }
      else {
        $max = 2;
      }
    }
    foreach ($goal_nodes as $goal_node) {
      $options[$goal_node->id()] = $goal_node->label();
    }
    
    $form['karmayo_pledge_activity'] = [
      '#type' => 'select',
      '#title' => t('Now choose an activity you want to finish within the next 24 hours'),
      '#options' => $options,
    ];
    $form['karmayo_points_at_stake'] = [
      '#type' => 'number',
      '#title' => 'Pick some points that can be gained if the task is done or lost if not.',
      '#required' => true,
      '#min' => 1,
      '#max' => $max,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nid = $form_state->getValue('karmayo_pledge_activity');
    $node = Node::load($nid);
    $user = \Drupal::currentUser();
    $user_data = User::load($user->id());
    $userName = $user_data->getUsername();
    $stake_points = $form_state->getValue('karmayo_points_at_stake');
    
    $data = [
      'nid' => $nid,
      'points' => $stake_points,
      'userName' => $userName,
      'activity' => $node->label()
    ];
    if (KarmayoCoreService::saveKarmayoPledgeEntity($data, 0)) {
      \Drupal\transaction\Entity\Transaction::create([
             'type' => 'userpoints_default_points',
             'target_entity' => $user_data,
             'field_userpoints_default_amount' => (-1 * $stake_points),
           ])->execute();
      drupal_set_message('Congratulations on finding your motivation! Hope you achieve your goal!');
      $form_state->setRedirect('view.my_tasks.page_1');
    }
  }

}
