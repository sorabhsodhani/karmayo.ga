<?php

/**
 * @file
 * Contains karmayo_society_pledge.page.inc.
 *
 * Page callback for Society user pledge entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Society user pledge entity templates.
 *
 * Default template: karmayo_society_pledge.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_karmayo_society_pledge(array &$variables) {
  // Fetch SocietyUserPledgeEntity Entity Object.
  $karmayo_society_pledge = $variables['elements']['#karmayo_society_pledge'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
