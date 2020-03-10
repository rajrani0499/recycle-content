<?php
namespace Drupal\recycle_content\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

class ContentRecycleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'content_recycle_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = array(
      '#type' => 'item',
      '#markup' => 'Action cannot be undone',
    );
    $form['recycle'] = [
      '#type' => 'submit',
      '#value' => 'Delete',
    ];
    $form['cancel'] = [
      '#type' => 'submit',
      '#value' => 'Cancel',
      '#submit' => ['::cancelForm'],
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $parameters = \Drupal::routeMatch()->getParameters();
    $NodeInfoArr = (array) $parameters;
    foreach ($NodeInfoArr as $key => $value) {
      if(isset($value['node']) && $node = Node::load($value['node'])) {
        if($node->getType() == 'article') {
          $node->set('field_recycle', 'recycle');
          $node->save();
        }else{
          $node->delete();
        }
        $response = new RedirectResponse('/admin/content');
        $response->send();
        return;
      }
    }
  }

  /**
   * Cancel form handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function cancelForm(array &$form, FormStateInterface $form_state) {
    $parameters = \Drupal::routeMatch()->getParameters();
    $NodeInfoArr = (array) $parameters;
    foreach ($NodeInfoArr as $key => $value) {
      if(isset($value['node']) && $node = Node::load($value['node'])) {
        if($node->getType() == 'article') {
          $node->set('field_recycle', 'draft');
          $node->save();
        }
        $response = new RedirectResponse('/admin/content');
        $response->send();
        return;
      }
    }
  }

}