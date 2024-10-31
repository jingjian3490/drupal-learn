<?php

namespace Drupal\Forcontu_hello\Test\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Page test.
 */
class PageTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'learn_theme';

  /**
   * {@inheritdoc}
   */
  public static $modules = ['block', 'node', 'forcontu_hello'];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    // Create user. Search content permission granted for the search block to
    // be shown.
    $this->drupalLogin($this->drupalCreateUser(['administer site configuration', 'access content']));
  }

  /**
   * T.
   */
  public function testHelloPage() {
    $assert = $this->assertSession();
    $this->drupalGet('/forcontu/hello');
    $assert->statusCodeEquals(200);
    $assert->pageTextContains('Hello,');
  }

}
