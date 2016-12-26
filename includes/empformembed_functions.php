<?php

function empformembed_register_styles() {

    // Styles are enqueued when shortcode is added to a page
    //wp_register_style( 'empformembed_css_reset', plugins_url('/includes/emp/plugins/css-reset/css-reset.css', dirname(__FILE__) ), array(), '1.0.0', all );
    wp_register_style( 'empformembed_bootstrap_emp', plugins_url('/includes/emp/plugins/bootstrap/css/bootstrap-emp.min.css', dirname(__FILE__) ), array(), '1.0.0', all );
    wp_register_style( 'empformembed_bootstrap_theme_emp', plugins_url('/includes/emp/plugins/bootstrap/css/bootstrap-theme-emp.min.css', dirname(__FILE__) ), array(), '1.0.0', all );
    wp_register_style( 'emp', plugins_url('/includes/emp/css/index.css', dirname(__FILE__) ), array(), '1.0.0', all );
}
add_action( 'wp_enqueue_scripts', 'empformembed_register_styles' );

// Returns the selected form name from the current post
function empformembed_get_form_custom_field($pid, $field) {
  $value = get_post_meta( $pid, $field, true );
  $value = ($value ? $value : false);
  return $value;
}

// Returns the AJAX result for the form submission
function empformembed_formhandler() {

  require_once(plugin_dir_path(__FILE__) . 'emp/scripts/config.php');

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
        
        $pid     = $_POST['pid'];
        $pid     = intval($pid);
        $api_key = empformembed_get_form_custom_field($pid, 'empformembed_api_key');
        $new_tab = empformembed_get_form_custom_field($pid, 'empformembed_new_tab');

        unset($_POST['pid']);        
        
        $_POST['IQS-API-KEY'] = $api_key;
        
        $phone_fields = $_POST['phone_fields'];
        $phone_fields = explode(',', $phone_fields);
        unset($_POST['phone_fields']);
        
        $html = '';
        
        $post_vars = array();
        foreach ($_POST as $k => $v) {
          if (in_array($k, $phone_fields)) {
            $v = preg_replace('/[^0-9]/', '', $v);
            $v = '%2B1' . $v;   // append +1 for US, but + needs to be %2B for posting
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
        
        $return['new_tab'] = $new_tab ? 1 : 0;
        $return['status'] = (isset($json->status) && $json->status == 'success') ? 1 : 0;
        $return['response'] = (isset($json->message)) ? $json->message : 'Something bad happened, please refresh the page and try again.';
        $return['data'] = (isset($json->data)) ? $json->data : '';
        
        break;
        
      default:
      
        break;
    }
  }

  echo json_encode($return);

  die();
}

add_action('wp_ajax_empformembed_formhandler', 'empformembed_formhandler');
add_action('wp_ajax_nopriv_empformembed_formhandler', 'empformembed_formhandler');