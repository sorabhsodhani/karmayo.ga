<?php

/**
 * @file
 * Contains karmayo_society_tasks.page.inc.
 *
 * Page callback for Karmayo Society Tasks Entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Karmayo Society Tasks Entity templates.
 *
 * Default template: karmayo_society_tasks.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_karmayo_society_tasks(array &$variables) {
  // Fetch SocietyTasksEntity Entity Object.
  $karmayo_society_tasks = $variables['elements']['#karmayo_society_tasks'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
