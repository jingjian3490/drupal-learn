<?php

namespace Drupal\forcontu_plugins\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\forcontu_plugins\FipsumPluginManager;
use Drupal\forcontu_plugins\ForcontuCoursesInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * T.
 */
class ForcontuPluginsController extends ControllerBase {

  /**
   * T.
   *
   * @var \Drupal\forcontu_plugins\FipsumPluginManager
   */
  protected $fipsum;

  /**
   * T.
   *
   * @var \Drupal\forcontu_plugins\ForcontuCoursesInterface
   */
  protected $forcontuCourses;

  public function __construct(FipsumPluginManager $fipsum, ForcontuCoursesInterface $forcontu_courses) {
    $this->fipsum = $fipsum;
    $this->forcontuCourses = $forcontu_courses;
  }

  /**
   * T.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.fipsum'),
       $container->get('forcontu.courses'),
    );
  }

  /**
   * T.
   */
  public function fipsum() {
    $lorem_ipsum = $this->fipsum->createInstance('lorem_ipsum');

    $build['fipsum_lorem_ipsum_title'] = [
      '#markup' => '<h2>' . $lorem_ipsum->description() . '</h2>',
    ];

    $build['fipsum_lorem_ipsum_text'] = [
      '#markup' => '<p>' . $lorem_ipsum->generate(300) . '</p>',
    ];

    $forcontu_ipsum = $this->fipsum->createInstance('forcontu_ipsum');

    $build['fipsum_forcontu_ipsum_title'] = [
      '#markup' => '<h2>' . $forcontu_ipsum->description() . '</h2>',
    ];

    $build['fipsum_forcontu_ipsum_text'] = [
      '#markup' => '<p>' . $forcontu_ipsum->generate(600) . '</p>',
    ];

    return $build;
  }

  /**
   * T.
   */
  public function courses() {
    $list = $this->forcontuCourses->getCourses();

    $header = [$this->t('Title'), $this->t('Tutor'), $this->t('Duration (months)'), $this->t('Hours')];

    $build['forcontu_plugins_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $list,
    ];
    return $build;
  }

}
