<?php

namespace Drupal\youwe_api;

use Drupal\Component\Serialization\Json;

class BeerClient {

  /**
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * BeerClient constructor.
   *
   * @param $http_client_factory \Drupal\Core\Http\ClientFactory
   */
  public function __construct($http_client_factory) {
    $this->client = $http_client_factory->fromOptions([
      'base_uri' => 'https://api.punkapi.com/v2/',
    ]);
  }

  /**
   * Get some random beer.
   * 
   * @return json
   */
  public function random() {
    $response = $this->client->get('beers/random');

    return Json::decode($response->getBody());
  }

  /**
   * Get beer based on food pairing.
   *
   * @param string $food
   *
   * @return array
   */
  public function search($food) {
    $response = $this->client->get('beers', [
      'query' => [
        'food' => $food,
        'per_page' => 3,
      ]
    ]);

    return Json::decode($response->getBody());
  }

}
