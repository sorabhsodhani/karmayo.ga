<?php

namespace Drupal\karmayo_pledge\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Plugin implementation of the update_pledge_status queueworker.
 *
 * @QueueWorker (
 *   id = "update_pledge_status",
 *   title = @Translation("Updates the status of pledge if exceeded 24 hours"),
 *   cron = {"time" = 60}
 * )
 */
class UpdatePledgeStatusQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // Process item operations.
  }

}
