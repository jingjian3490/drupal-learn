<?php

namespace Drupal\forcontu_pages\Routing;

use Symfony\Component\Routing\Route;

/**
 * Provides dynamic routes for forcontu_pages.
 */
class ForcontuPagesRoutes {

  /**
   * Returns an array of route objects.
   *
   * @return \Symfony\Component\Routing\Route[]
   *   An array of route objects.
   */
  public function routes(): array {
    $routes = [];
    $node_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
    foreach ($node_types as $type) {
      $routes["forcontu_pages.{$type->id()}.help"] = new Route(
        '/forcontu/pages/node/' . $type->id() . '/help',
        [
          '_controller' => '\Drupal\forcontu_pages\Controller\ForcontuPagesController::contentTypeHelpPage',
          '_title' => 'Content Type ' . $type->label(),
          'path' => $type->id(),
          'title' => $type->label(),
        ],
        [
          '_permission' => 'access content',
        ]
      );
    }

    return $routes;
  }

}
