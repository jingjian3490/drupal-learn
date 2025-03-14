<?php

namespace Drupal\forcontu_hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TD.
 */
class ForcontuHelloController extends ControllerBase {


  protected $database;

  protected $currentUser;

  /**
   * T.
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->database = $container->get('database');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

  /**
   * TD.
   */
  public function hello(): array {
    return [
      '#markup' => '<p>' . $this->t('Hello, world! <a href = :a > 22 </a>, current user is @user',
          [':a' => 'https://drupal-learn.ddev.site/', '@user' => $this->currentUser->id()]) . '</p>',
    ];
  }

}
