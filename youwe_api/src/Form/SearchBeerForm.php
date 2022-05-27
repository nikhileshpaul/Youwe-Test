<?php
/**
 * @file
 * Contains \Drupal\youwe_api\Form\SearchBeerForm.
 */
namespace Drupal\youwe_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\youwe_api\BeerClient;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SearchBeerForm extends FormBase {

  /**
   * @var \Drupal\youwe_api\BeerClient
   */
  protected $beerClient;

  /**
   * Class constructor.
   */
  public function __construct(BeerClient $beer_client) {
    $this->beerClient = $beer_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('youwe_beer_client')
    );
  }    
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_beer_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
        '#type' => 'markup',
        '#markup' => '<div class="result_message"></div>',
    ];

    $form['food'] = array(
      '#type' => 'textfield',
      '#title' => t('Food Name:'),
      '#required' => TRUE,
    );

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::showBeers',
      ],
    );

    return $form;
  }

  public function showBeers(array $form, FormStateInterface $form_state) {
 
    $food = $form_state->getValue('food');
    $beers = $this->beerClient->search($food);

    $resultmarkup = '<div class="beers">';
    if (empty($beers)) {
        $resultmarkup .= '<div><strong>No Beers found</strong></div>';
    } else {
        $resultmarkup .= '<div>
                            <strong>You can try the below beers:</strong>
                            <table>
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
    }


    $resultmarkup .= '</div>';

    $response = new AjaxResponse();
    $response->addCommand(
        new HtmlCommand(
            '.result_message',
            '<div class="my_message">' . $resultmarkup . '</div>')
        );
    return $response;
  }  

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
}