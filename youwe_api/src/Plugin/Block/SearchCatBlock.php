<?php

namespace Drupal\youwe_api\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "search_cat_block",
 *   admin_label = @Translation("Search Cat Block")
 * )
 */
class SearchCatBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    
    $form = \Drupal::formBuilder()->getForm('Drupal\youwe_api\Form\SearchCatForm');
    return $form;
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }  

}
