<?php

namespace Drupal\youwe_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Markup;

/**
 * @Block(
 *   id = "random_beer_block",
 *   admin_label = @Translation("Random Beer Block")
 * )
 */
class RandomBeerBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\youwe_api\BeerClient
   */
  protected $beerClient;

  /**
   * RandomBeerBlock constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param $beerClient \Drupal\youwe_api\BeerClient
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $beer_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->beerClient = $beer_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('youwe_beer_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $beers = $this->beerClient->random();

    $resultmarkup = '<div class="random_beer">';

    $resultmarkup .= '<table>
                        <tr>
                            <th>Title</th>
                            <th>Tagline</th>
                            <th>ABV</th>
                            <th>Image</th>
                        </tr>';
    foreach ($beers as $key => $beer) {
        $resultmarkup .= '<tr>
                            <td>' . $beer['name']  . '</td>
                            <td>' . $beer['tagline']  . '</td>
                            <td>' . $beer['abv']  . '</td>
                            <td><img src="' . $beer['image_url'] . '" width="30" height="30"></td>
                          </tr>';
    }
    $resultmarkup .= '</table>
                    </div>';
    return [
      '#markup' => Markup::create($resultmarkup),
    ];    
  }  

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }  

}
