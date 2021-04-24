<?php

namespace Drupal\karmayo_society;
use Drupal\karmayo_society\Entity\KarmayoSocietyEntity;
use Drupal\karmayo_society\Entity\SocietyTasksEntity;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\user\Entity\User;
use Drupal\karmayo_society\Entity\SocietyUserPledgeEntity;
use Drupal\karmayo_society\Entity\SocietyUserPointsEntity;

/**
 * Class KarmayoSocietyCoreService.
 */
class KarmayoSocietyCoreService {

  /**
   * Checks if user is moderator for any other societies.
   */
  public static function isUserModeratorForOtherSocieties($uid = NULL, $society_id = NULL) {
    $query = \Drupal::entityQuery('karmayo_society')
      ->condition('society_owner_id','userpoints_default_points')
      ->condition('id',$society_id,'!=')
      ->execute(); 
    if (!empty($query)) {
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * Removes a role for a given user.
   */
  public static function removeUserRole ($user = NULL, $role = NULL) {
    if ($user != NULL && $role != NULL) {
      $user->removeRole($role);
      if ($user->save()) {
        return TRUE;
      }
    }
    return FALSE;
  }
  
  /**
   * Adds a role for a given user.
   */
  public static function addUserRole ($user = NULL, $role = NULL) {
    if ($user != NULL && $role != NULL) {
      $user->addRole($role);
      if ($user->save()) {
        return TRUE;
      }
    }
    return FALSE;
  }
  
  /**
   * Saves users associated to society.
   */
  public static function saveTasksOfSociety ($tasks_data = NULL, $sid = NULL, $task_date = NULL) {
    $results = [];
    foreach ($tasks_data as $task) {
      $task_entity = SocietyTasksEntity::create([
        'name' => $task[0],
        'society_task_pointfactor' => $task[1],
        'society_id' => $sid,
        'society_task_start_date' => date('Y-m-d', strtotime($task_date))
      ]);
      if ($task_entity->save()) {
        $results[] = $task_entity->id();
      }
      else {
        \Drupal::logger('karmayo_society_task_saving')->error('Saving of task named ' . $task[0] . ' uploaded on ' . $task_date . ' for Society with ID ' . $sid . ' failed. Please check.');
        $results[] = FALSE;
      }
    }
    return $results;
  }
  
  /**
   * Saves users associated to society.
   */
  public static function saveUsersOfSociety ($users_data = NULL, $sid = NULL) {
    $flag = TRUE;
    $error_msg = [];
    foreach ($users_data as $user_data) {
      $user = User::create();
     // $user->setPassword("password");
      $user->setEmail($user_data[1]);
      $user->setUsername($user_data[2]); //This username must be unique and accept only a-Z,0-9, - _ @ .
      $user->set('field_user_display_name', $user_data[0]);
      $user->set('field_user_associated_societies', $sid);
      $user->addRole('karmayo_society_user'); //E.g: authenticated
      $user->set('init', 'email');
      $user->activate();
      $violations = $user->validate();
      if ($violations != NULL) {
        if ($user->save()) {
           _user_mail_notify('register_admin_created', $user);
           KarmayoSocietyCoreService::updateSocietyUserPoints($user, $sid, 'register');
        }
        else {
          $error_msg[$user_data[2]] = 'Failed while saving user entity';
          \Drupal::logger('karmayo_society_task_saving')->error('Saving of user named ' . $user_data[0] . ' for Society with ID ' . $sid . ' failed. Please check.');
        }
      }
      else {
        $error_msg[$user_data[2]] = $voilations;
      }
    }
    if (!$flag) {
      return $error_msg;
    }
    else {
      return $flag;
    }
  }
  
  /**
   * Map tasks to all users associated to society.
   */
  public static function mapTasksToUsers ($tasks_ids = NULL, $sid = NULL, $task_date = NULL, $users_data = NULL, $stake_points = NULL) {
    if ($users_data == NULL) {
      $userIds_of_society = KarmayoSocietyCoreService::getUserOfSociety($sid);
      if (!empty($userIds_of_society)) {
        $users_data = User::loadMultiple($userIds_of_society);
      }
    }
    if ($stake_points == NULL) {
      $stake_points = 0;
    }

    $results = [];
    foreach ($users_data as $user_data) {
      foreach ($tasks_ids as $task_id) {
        if (!is_bool($task_id)) {
        
          $taskData = SocietyTasksEntity::load($task_id);
          if ($taskData != NULL) {
            $map_task = SocietyUserPledgeEntity::create([
              'name' => $user_data->get('field_user_display_name')->value . ' - ' . $taskData->getName(),
              'society_pledge_user_id' => $user_data->id(),
              'society_pledge_societyid' => $sid,
              'society_pledge_taskid' => $task_id,
              'society_activity_points' => $stake_points
             ]); 
            if ($map_task->save()) {
              //kint('saveddd');exit;
              $results[] = TRUE;
            }
            else {
              $results[] = FALSE;
              \Drupal::logger('karmayo_society_map_tasks')->error('Mapping of task with id ' . $task_id . ' for user with ID ' . $user_data->id() . ' failed. Please check.');
            }
          }
        }
      }
    }
  }
  
  public static function getUserOfSociety ($sid = NULL) {
    $query = \Drupal::service('entity.query')
      ->get('user')
      ->condition('field_user_associated_societies', [$sid], 'IN');
    $userIds = $query->execute();
    return array_values($userIds);
  }
  
  public static function updateSocietyUserPoints ($user = NULL, $sid = NULL, $op = 'register', $amount = NULL) {
    if ($op == 'register') {
      $amount = 100;
    }
    $updated_balance = $amount + $balance;
    $user_point_entity = SocietyUserPointsEntity::create([
      'name' => $user->get('field_user_display_name')->value,
      'society_points_societyid' => $sid,
      'user_id' => $user->id(),
      'society_user_points_amount' => $amount,
    ]);
    if ($user_point_entity->save()) {
      return TRUE;
    }
    else {
      \Drupal::logger('karmayo_society_user_points')->error('There has been an issue while updating the user uid ' . $user->id() . ' for sid ' . $sid);
    }
  }
  
  public static function getSocietyUserBalancePoints($user = NULL, $sid = NULL) {
     $query = \Drupal::service('entity.query')
      ->get('karmayo_society_user_points')
      ->condition('society_points_societyid', $sid)
      ->condition('user_id', $user->id())
      ->sort('created', 'DESC')
      ->range(0, 1);
    $results = $query->execute();
    
    if (!empty($results)) {
      $points_data = SocietyUserPointsEntity::load(reset(array_values($results)));
      return $points_data->get('society_user_points_balance')->value;
    }
    else {
      return 0;
    }
    
  }
}
