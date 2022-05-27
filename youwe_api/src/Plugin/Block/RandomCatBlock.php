<?php

namespace Drupal\youwe_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Markup;

/**
 * @Block(
 *   id = "random_cat_block",
 *   admin_label = @Translation("Random Cat Block")
 * )
 */
class RandomCatBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\youwe_api\CatClient
   */
  protected $catClient;

  /**
   * RandomCatBlock constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param $catClient \Drupal\youwe_api\CatClient
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $cat_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->catClient = $cat_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('youwe_cat_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cats = $this->catClient->random();

    $return_html = '<div class="random_cat">';

    foreach ($cats as $cat) {
      $return_html .= '<img src="' . $cat['url'] . '" width="200" height="200">';
    }

    $return_html .= '</div>';

    return [
        '#markup' => Markup::create($return_html),
    ];
  }  

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }  

}
