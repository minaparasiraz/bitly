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
          '#header' => array('ID', 'Source URL', 'Short URL','Hits'),
          '#rows' => array(),
      );
      global $base_url;
      
      
      foreach($query as $row) {
          $table['#rows'][] = array($row->id, $row->sourceurl, $base_url."/".$row->short_code,$row->hits);
      }
      return $table;
  }

  public function showDetails($shortcode) {  

      global $base_url;
      $data = [];
      $db = \Drupal::database(); 
      $query = $db->select('bitlytable', 'n'); 
      $query->condition('n.short_code', $shortcode);
      $query->fields('n.*'); 
      $data = $query->execute()->fetchAll();     
           
      
       return [
      '#theme' => 'codimth',
      '#id' => $data[0]->id,
      '#sourceurl' => $data[0]->sourceurl,
      '#short_code' => $base_url."/".$data[0]->short_code
     
    ];
  }
}