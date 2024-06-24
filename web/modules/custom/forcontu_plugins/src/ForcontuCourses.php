<?php

namespace Drupal\forcontu_plugins;

/**
 * T.
 */
class ForcontuCourses implements ForcontuCoursesInterface {

  /**
   * T.
   */
  protected $courses;

  public function __construct($courses) {
    $this->courses = $courses;
  }

  /**
   * T.
   */
  public function getCourses() {
    return $this->courses;
  }

}
