<?php

namespace Drupal\cargaview\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Plugin implementation of the 'carga_view' formatter.
 *
 * @FieldFormatter(
 *   id = "carga_view",
 *   label = @Translation("Carga View"),
 *   field_types = {
 *     "daterange"
 *   }
 * )
 */
class CargaViewFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'separator' => '+',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $separator = $this->getSetting('separator');

    foreach ($items as $delta => $item) {
      if (!empty($item->start_date) && !empty($item->end_date)) {
        /** @var \Drupal\Core\Datetime\DrupalDateTime $start_date */
        $start_date = $item->start_date;
        /** @var \Drupal\Core\Datetime\DrupalDateTime $end_date */
        $end_date = $item->end_date;

        if ($start_date->getTimestamp() !== $end_date->getTimestamp()) {

          $date_diff = $end_date->getTimestamp() - $start_date->getTimestamp();
          $day_diff = floor($date_diff / (60 * 60 * 24));

          $elements[$delta] = [
            '#type' => 'markup',
            '#markup' => \Drupal::service('date.formatter')->format($start_date->getTimestamp(), 'custom', 'd-M') . ' ' . $separator . $day_diff . 'd',
          ];
        }
        else {
          $elements[$delta] = [
            '#type' => 'markup',
            '#markup' => \Drupal::service('date.formatter')->format($start_date->getTimestamp(), 'custom', 'd-M'),
          ];

          if (!empty($item->_attributes)) {
            $elements[$delta]['#attributes'] += $item->_attributes;
            // Unset field item attributes since they have been included in the
            // formatter output and should not be rendered in the field template.
            unset($item->_attributes);
          }
        }
      }
    }

    return $elements;
  }
}
