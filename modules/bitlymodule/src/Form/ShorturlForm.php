<?php

namespace Drupal\bitlymodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Component\Utility\UrlHelper;

/**
 * Provides the form for adding countries.
 */
class ShorturlForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add-short-url';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    
    $form['sourceurl'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source URL'),
      '#default_value' => '',
          
      
    ];
    /*
	 $form['sname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Short URL'),
      '#required' => TRUE,
      '#maxlength' => 20,
      '#default_value' =>  '',
    ];
	*/
	
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#default_value' => $this->t('Save') ,
    ];
	
	//$form['#validate'][] = 'studentFormValidate';

    return $form;

  }
  
   /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {
       $field = $form_state->getValues();
	   
		$fields["sourceurl"] = $field['sourceurl'];

		if (!$form_state->getValue('sourceurl') || empty($form_state->getValue('sourceurl'))) {
            $form_state->setErrorByName('sourceurl', $this->t('Provide Source URL'));
        }

        // Validate Formate of URL

        $validate_url = UrlHelper::isValid($form_state->getValue('sourceurl'), $absolute = TRUE);
       
        if (!$validate_url) {
            $form_state->setErrorByName('sourceurl', $this->t('Provide valid URL'));
        }

        
		
		
  }

   public function generateRandomString($length = 6){

         $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";

        $sets = explode('|', $chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        return $randString;
    }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {
    	try{
        		$conn = Database::getConnection();
        		
        		$field = $form_state->getValues();
        	   
        		$fields["sourceurl"] = $field['sourceurl'];

            $fields["short_code"] = $this->generateRandomString(7);       		

        		 
        		//exit;
        				
        		  $conn->insert('bitlytable')
        			   ->fields($fields)->execute();

        			$url = Url::fromRoute('bitlymodule.short-url-list');
            	$form_state->setRedirectUrl($url);
    		 
        	} catch(Exception $ex){
        		  \Drupal::logger('bitlymodule')->error($ex->getMessage());
        	}
        
      }

}