<?php
/*
function empformembed_get_form_data($form_id) {

  $data = array(
    //'nursingcas' => array('api_key' => '8922994c9ffb387f43d0bc0438b8b1089ebfdfe2', 'client_id' => '218'),
    'psycas'        => array('api_key' => '63df2104d07dcc5fe903d8886736a43d53d15add', 'client_id' => '258'),
    'otcas'         => array('api_key' => '25fa30732cdd0d4df25389af92d06cd622779a7e', 'client_id' => '281'),
    'atcas'         => array('api_key' => '579b04becd966c3628375edc06f625bd3e91c1f3', 'client_id' => '288'),
    'psmcas'        => array('api_key' => '51454b71d87a5e6bf9bfc130ce5d2d3d1d34f4fc', 'client_id' => '296'),
    'csdcas'        => array('api_key' => 'bf80df844736b319e58cfdc6482ab372937aed79', 'client_id' => '304'),
    'oprescas'      => array('api_key' => '3979a2f17b48864429335d151089fc9ce3196ffa', 'client_id' => '305'),
    'otacas'        => array('api_key' => 'bdb2ba57ef783836b67bfcf350dd8f32c6b837dd', 'client_id' => '312'),
    'nafcas'        => array('api_key' => '064b9682d2d3e3882dc4fe5660af9b32c531c441', 'client_id' => '317'),
    'socialworkcas' => array('api_key' => '3251233c60ffba2f3116badabc0a2d3412cd451d', 'client_id' => '310'),
    'mftcas'        => array('api_key' => 'a21c5e6daa8df9a6aabe4e9c8e40ca853ead37b6', 'client_id' => '287'),
  );

  if (!$form_id) {

    if (isset($_POST['form_id'])) {
      $form_id = strip_tags(trim($_POST['form_id']));
    } else {
      // No form data found
      return false;
    }
  }

  $form_data = $data[$form_id];

  return $form_data;
}
*/

/*
$form_id = empformembed_get_form_custom_field();
$form = empformembed_get_form_data($form_id);
*/
$pid       = $a['pid'];
$api_key   = empformembed_get_form_custom_field($pid, 'empformembed_api_key');
$client_id = empformembed_get_form_custom_field($pid, 'empformembed_client_id');

$config['SpectrumEMPAPIKey'] = $api_key;
$config['SpectrumEMPClientID'] = $client_id;

// For WordPress implementation only
// Provides an input[type=hidden] value to be
// passed via AJAX outside of The Loop
//$config['HiddenFormID'] = $form_id;

// these typically won't need to be modified
define('API_URL', 				'https://www.spectrumemp.com/api/');
define('REQUIREMENTS_URL', 		API_URL . 'inquiry_form/requirements');
define('SUBMIT_URL', 			API_URL . 'inquiry_form/submit');
define('CLIENT_RULES_URL', 		API_URL . 'field_rules/client_rules');
define('FIELD_OPTIONS_URL', 	API_URL . 'field_rules/field_options');

?>