<?php
/**
 * @file
 * Contains \Drupal\youwe_api\Form\SearchCatForm.
 */
namespace Drupal\youwe_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\youwe_api\CatClient;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SearchCatForm extends FormBase {

  /**
   * @var \Drupal\youwe_api\CatClient
   */
  protected $catClient;

  /**
   * Class constructor.
   */
  public function __construct(CatClient $cat_client) {
    $this->catClient = $cat_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('youwe_cat_client')
    );
  }    
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_cat_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $breeds = $this->catClient->getBreeds();
    $breed_ids = [];
    foreach ($breeds as $breed) {
        $breed_ids[$breed['id']] = $breed['name'];
    }

    $form['message'] = [
        '#type' => 'markup',
        '#markup' => '<div class="cat_message"></div>',
    ];

    $form['breed'] = [
        '#type' => 'select',
        '#title' => $this->t('Breeds'),
        '#options' => $breed_ids,
    ];
  

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::showCats',
      ],
    );

    return $form;
  }

  public function showCats(array $form, FormStateInterface $form_state) {
 
    $breed_id = $form_state->getValue('breed');

    $breed = $this->catClient->getCats($breed_id);

    $resultmarkup = '<div class="cats">';
    if (empty($breed)) {
        $resultmarkup .= '<div><strong>No Breed found</strong></div>';
    } else {
        $resultmarkup .= '<div>
                            <strong>Below are the breed details:</strong>
                            <table>
                                <tr>
                                    <th>Breed name</th>
                                    <th>Image</th>
                                    <th>Temperament</th>
                                    <th>Description</th>
                                    <th>Wiki Link</th>
                                </tr>
                                </tr>
                                    <td>' . $breed[0]['breeds'][0]['name'] . '</td>
                                    <td><img src="' . $breed[0]['url'] . '"></td>
                                    <td>' . $breed[0]['breeds'][0]['temperament'] . '</td>
                                    <td>' . $breed[0]['breeds'][0]['description'] . '</td>
                                    <td>' . $breed[0]['breeds'][0]['wikipedia_url'] . '</td>
                                </tr>';
        $resultmarkup .= '</table>
                        </div>';
    }


    $resultmarkup .= '</div>';

    $response = new AjaxResponse();
    $response->addCommand(
        new HtmlCommand(
            '.cat_message',
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