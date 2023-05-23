<?php


use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once 'autoload.php';


$kernel = new DrupalKernel('prod', $autoloader);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$con = \Drupal\Core\Database\Database::getConnection('default');


if($_GET["c"]=="")
{
  echo "No short code was supplied.";
  exit;
}

$shortcode = $_GET["c"];
$table = 'bitlytable';

 	  $query = $con->select($table, 'n'); 
      $query->condition('n.short_code', $shortcode);
      $query->fields('n.*'); 
      $data = $query->execute()->fetchAll(); 

if($data[0]->id=="")
{
  echo "Short code does not appear to exist.";
  exit;
}
      
\Drupal::database()->update($table)->expression('hits', 'hits + 1')
  ->condition('id', $data[0]->id)
  ->execute();

   header("Location: ".$data[0]->sourceurl);
    exit; 

?>