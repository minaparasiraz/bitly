<?php
namespace Drupal\bitlymodule\Controller;
 
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Database\Database;
 
/**
 * Provides route responses for the Example module.
 */
class ShorturlController extends ControllerBase {
 
 
 
  public function listDetails() {  

      $db = \Drupal::database(); 
      $query = $db->select('bitlytable', 'n'); 
      $query->fields('n.*'); 
      $query = $query->execute()->fetchAll();     
      $table = array(
          '#theme' => 'table',
          '#header' => array('ID', 'Source URL', 'Short URL'),
          '#rows' => array(),
      );
      foreach($query as $row) {
          $table['#rows'][] = array($row->id, $row->sourceurl, $row->shorturl);
      }
      return $table;
  }

  public function showDetails($shortcode) {  

      /*

      $db = \Drupal::database(); 
      $query = $db->select('bitlytable', 'n'); 
      $query->condition('n.shorturl', $shortcode);
      $query->fields('n.*'); 
      $query = $query->execute()->fetchAll();     

      */

      
      
       return [
      '#theme' => 'show_details',
      '#version' => 'Drupal8',
     
    ];
  }
}