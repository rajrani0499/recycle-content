<?php

namespace Drupal\recycle_content\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RecycleDeleteRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('entity.node.delete_form')) {
      $defaults = $route->getDefaults();
      unset($defaults['_entity_form']);
      $defaults['_form'] = '\Drupal\recycle_content\Form\ContentRecycleForm';
      $route->setDefaults($defaults); 
      $route->setRequirements(['_permission' => 'access content']);
    }
  }

}