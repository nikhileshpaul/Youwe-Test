<?php

namespace Drupal\youwe_api;

use Drupal\Component\Serialization\Json;

class CatClient {

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
      'base_uri' => 'https://api.thecatapi.com/v1/',
    ]);
  }

  /**
   * Get some random cat.
   *
   * @return json
   */
  public function random() {
    $response = $this->client->get('images/search' , [
        'query' => [
            'limit' => 1,   
        ]
    ]);

    return Json::decode($response->getBody());
  }

  /**
   * Get cat based on breed.
   *
   * @param string $breed
   *
   * @return array
   */
  public function getBreeds() {
    $response = $this->client->get('breeds');

    return Json::decode($response->getBody());
  }

  /**
   * Get cat based on breed.
   *
   * @param string $breed
   *
   * @return array
   */
  public function getCats($breed_id) {
    $response = $this->client->get('images/search', [
        'query' => [
          'breed_id' => $breed_id,
        ]
      ]);

      return Json::decode($response->getBody());
  }  

}
