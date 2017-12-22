<?php

namespace Drupal\bid\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'bidForm' block.
 *
 * @Block(
 *  id = "bid_form",
 *  admin_label = @Translation("Bid form"),
 * )
 */
class bidForm extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $user = \Drupal::currentUser();

    $carga_node = \Drupal::routeMatch()->getParameter('node');
    if (isset( $carga_node ) && $carga_node->getType() == 'carga') {
      $status = $carga_node->get('field_carga_status')->getValue();
      if( $status[0]['target_id'] == 1) {
        // Check if user already submitted a bid.
        $bid_user = views_get_view_result('mis_ofertas', 'default', $user->id(), $carga_node->id());
        if (count($bid_user)) {
          if (isset($_GET['op']) && $_GET['op'] == 'edit') {
            $n = Node::load($bid_user[0]->nid);
            $form = $this->_bid_node_form('oferta', $carga_node->id(), $n);
            $build['bid_form']['#markup'] = render(\Drupal::formBuilder()->getForm($form));
          } else {
            $bid = views_embed_view('mis_ofertas', 'default', $user->id(), $carga_node->id());
            $build['view']['#markup'] = render($bid);
            $build['bid_form']['#markup'] = render(\Drupal::formBuilder()->getForm('\Drupal\bid\Form\CurrentOptionsForm'));
          }
        } else {
          $form = $this->_bid_node_form('oferta', $carga_node->id());
          $build['bid_form']['#markup'] = render(\Drupal::formBuilder()->getForm($form));
        }
      } else {
        $term = taxonomy_term_load($status[0]['target_id']);
        $build['bid_form']['#markup'] = '<div><span>Estado:</span>'.$term->get('name')->value.'</div>';
      }


    } else {
      $build['bid_form']['#markup'] = 'This block is only visible in carga node view';
    }

    $build['bid_form']['#cache']['max-age'] = 0;

    return $build;
  }

  public function _bid_node_form($type, $cid, $node = NULL) {
    $form = NULL;
    if ($node == NULL) {
      $values = ['type' => $type, 'field_ofert_carga' => $cid];
      $node = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->create($values);
    }
    $form = \Drupal::entityTypeManager()
      ->getFormObject('node', 'default')
      ->setEntity($node);
    return $form;
  }

}
