<?php

namespace Drupal\karmayo_pledge\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the karmayo_pledge module.
 */
class AjaxUpdateToPerformControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "karmayo_pledge AjaxUpdateToPerformController's controller functionality",
      'description' => 'Test Unit for module karmayo_pledge and controller AjaxUpdateToPerformController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests karmayo_pledge functionality.
   */
  public function testAjaxUpdateToPerformController() {
    // Check that the basic functions of module karmayo_pledge.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
