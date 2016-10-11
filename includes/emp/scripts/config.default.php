<?php

$config['SpectrumEMPAPIKey'] = "INSERT_YOUR_SPECTRUM_EMP_API_KEY_HERE";
$config['SpectrumEMPClientID'] = "INSERT_YOUR_SPECTRUM_EMP_CLIENT_ID_HERE";

// these typically won't need to be modified
define('API_URL', 				'https://www.spectrumemp.com/api/');
define('REQUIREMENTS_URL', 		API_URL . 'inquiry_form/requirements');
define('SUBMIT_URL', 			API_URL . 'inquiry_form/submit');
define('CLIENT_RULES_URL', 		API_URL . 'field_rules/client_rules');
define('FIELD_OPTIONS_URL', 	API_URL . 'field_rules/field_options');

?>