<?php

/**
 * @file
 * Contains \Drupal\search\SearchPageAccessControlHandler.
 */

namespace Drupal\search;

use Drupal\Core\Access\AccessibleInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the search page entity type.
 *
 * @see \Drupal\search\Entity\SearchPage
 */
class SearchPageAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    /** @var $entity \Drupal\search\SearchPageInterface */
    if (in_array($operation, array('delete', 'disable')) && $entity->isDefaultSearch()) {
      return FALSE;
    }
    if ($operation == 'view') {
      if (!$entity->status()) {
        return FALSE;
      }
      $plugin = $entity->getPlugin();
      if ($plugin instanceof AccessibleInterface) {
        return $plugin->access($operation, $account);
      }
      return TRUE;
    }
    return parent::checkAccess($entity, $operation, $langcode, $account);
  }

}