<?php

/**
 * @file
 * Contains karmayo_society_user_points.page.inc.
 *
 * Page callback for Society user points entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Society user points entity templates.
 *
 * Default template: karmayo_society_user_points.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_karmayo_society_user_points(array &$variables) {
  // Fetch SocietyUserPointsEntity Entity Object.
  $karmayo_society_user_points = $variables['elements']['#karmayo_society_user_points'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
