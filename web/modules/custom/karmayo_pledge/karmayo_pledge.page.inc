<?php

/**
 * @file
 * Contains karmayo_pledge.page.inc.
 *
 * Page callback for Karmayo Pledge entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Karmayo Pledge templates.
 *
 * Default template: karmayo_pledge.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_karmayo_pledge(array &$variables) {
  // Fetch KarmayoPledgeEntity Entity Object.
  $karmayo_pledge = $variables['elements']['#karmayo_pledge'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
