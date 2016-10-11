<?php

require_once('config.php');

$return = array();

$return['status'] = 0;
$return['response'] = 'Undefined action';

if (count($_GET) > 0) {
	foreach ($_GET as $key => $val) {
		if (get_magic_quotes_gpc()) {
			$_GET[$key] = stripslashes($val);
		}
		
		$_GET[$key] = trim($val);
	}
}

if (count($_POST) > 0) {
	foreach ($_POST as $key => $val) {
		if (get_magic_quotes_gpc()) {
			$_POST[$key] = stripslashes($val);
		}
		
		$_POST[$key] = trim($val);
	}
}

if (isset($_POST['form_submit'])) {
	switch ($_POST['form_submit']) {
		
		case 'form_example':
			
			unset($_POST['form_submit']);
			$_POST['IQS-API-KEY'] = $config['SpectrumEMPAPIKey'];
			
			$phone_fields = $_POST['phone_fields'];
			$phone_fields = explode(',', $phone_fields);
			unset($_POST['phone_fields']);
			
			$html = '';
			
			$post_vars = array();
			foreach ($_POST as $k => $v) {
				if (in_array($k, $phone_fields)) {
					$v = preg_replace('/[^0-9]/', '', $v);
					$v = '%2B1' . $v;		// append +1 for US, but + needs to be %2B for posting
				}
				// if this checkbox field is set then it was checked
				if (stripos($k, '-text-opt-in') !== false) {
					$v = '1';
				}
				$post_vars[] = $k . '=' . $v;
			}
			$post_vars = implode('&', $post_vars);
			
			$ch = curl_init(SUBMIT_URL);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vars);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$raw_html = curl_exec($ch);
			$curl_errno = curl_errno($ch);
			$curl_error = curl_error($ch);
			curl_close($ch);
			
			$json = json_decode($raw_html);
			
			$return['status'] = (isset($json->status) && $json->status == 'success') ? 1 : 0;
			$return['response'] = (isset($json->message)) ? $json->message : 'Something bad happened, please refresh the page and try again.';
			$return['data'] = (isset($json->data)) ? $json->data : '';
			
			break;
			
		default:
		
			break;
	}
}

echo json_encode($return);

?>