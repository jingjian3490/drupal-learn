<?php

namespace Drupal\forcontu_ajax\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * T.
 */
class ForcontuAjaxAutocompleteController extends ControllerBase {

  /**
   * T.
   */
  public function userAutocomplete(Request $request): JsonResponse {
    $string = $request->query->get('q');
    $users = ['admin', 'foo', 'foobar', 'foobaz'];
    $matches = preg_grep("/$string/i", $users);
    return new JsonResponse(array_values($matches));
  }

}
