<?php

namespace Drupal\forcontu_events\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class ForcontuEventsRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // 改变 '/user/login' 路由为 '/pfizer-login'.
    if ($route = $collection->get('user.login')) {
      $route->setPath('/pfizer-login');
    }

    // 拒绝访问用户配置文件页面.
    if ($route = $collection->get('entity.user.canonical')) {
      $route->setRequirement('_access', 'FALSE');
    }
  }

}
