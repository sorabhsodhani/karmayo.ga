<?php

/**
 * @file
 * Contains karmayo_society.page.inc.
 *
 * Page callback for Karmayo society entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Karmayo society entity templates.
 *
 * Default template: karmayo_society.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_karmayo_society(array &$variables) {
  // Fetch KarmayoSocietyEntity Entity Object.
  $karmayo_society = $variables['elements']['#karmayo_society'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
