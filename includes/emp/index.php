<?php

if (!file_exists('scripts/config.php')) {
	exit('Please copy scripts/config.default.php to scripts/config.php and fill in the API variables with the ones provided to you.');	
}

require_once('scripts/config.php');
require_once('scripts/functions.php');

// update when css/js changes to bust the cache
$cache = '0.01';

$form = getFormFromSpectrumEMPAPI($config['SpectrumEMPAPIKey']);

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Spectrum EMP API Example</title>
        
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-language" content="en" />
		
		<!-- resources -->
		<link href="plugins/css-reset/css-reset.css?<?= $cache ?>" rel="stylesheet" type="text/css" />
		<link href="plugins/jquery/jquery-ui.css?<?= $cache ?>" rel="stylesheet" type="text/css" />
		
		<!-- bootstrap css -->
		<link href="plugins/bootstrap/css/bootstrap.min.css?<?= $cache ?>" rel="stylesheet" type="text/css" />
		<link href="plugins/bootstrap/css/bootstrap-theme.min.css?<?= $cache ?>" rel="stylesheet" type="text/css" />
		
		<!-- page specific -->
		<link href="css/index.css?<?= $cache ?>" rel="stylesheet" type="text/css" />
		
		<!-- hide everything if javascript is disabled -->
		<noscript>
			<style type="text/css">
				#body {
					display: none;
				}
				#javascript-disabled-message {
					width: 600px;
					margin: 0 auto;
					padding: 15px;
					text-align: left;
					margin-top: 220px;
					background: #e5e5e5;
				}
			</style>
		</noscript>
		
		<script>
			window.SITE = {};
		</script>
    </head>
    <body>
    	<!-- inform the user that javascript is a required element to view this page -->
		<noscript>
			<div id="javascript-disabled-message">
				<div class="page-header">
					<h1>Javascript is currently disabled...</h1>
				</div>
				<p>
					This page requires a browser feature called JavaScript. All modern browsers support JavaScript. You probably just
					need to change a setting in order to turn it on.
				</p>
				<p>
					Please see: <a href="http://www.google.com/support/bin/answer.py?answer=23852" target="_blank">How to enable
					JavaScript in your browser</a>.
				</p>
				<p>
					If you use ad-blocking software, it may require you to allow JavaScript from this site. Once you've enabled JavaScript
					you can <a href="/index.php/inquiryform">try loading this page again</a>.
				</p>
				<p>
					Thank you.
				</p>
			</div>
		</noscript>
		
		<div id="body">
		    <div class="container">
		    	<?= $form['html']; ?>
		    </div>
		</div>
		
		<?= implode('', $form['modals']); ?>
		
		<!-- resources -->
		<script type="text/javascript" src="plugins/jquery/jquery.js?<?= $cache ?>"></script>
		<script type="text/javascript" src="plugins/jquery/jquery-ui.js?<?= $cache ?>"></script>
		<script type="text/javascript" src="plugins/jquery/jquery-masked.js?<?= $cache ?>"></script>
		<script type="text/javascript" src="plugins/jquery/jquery-pubsub.js?<?= $cache ?>"></script>
		<script type="text/javascript" src="plugins/iqs/validate.js?<?= $cache ?>"></script>
		
		<!-- page specific -->
		<script type="text/javascript" src="js/field_rules_form_library.js?<?= $cache ?>"></script>
		<script type="text/javascript" src="js/field_rules_handler.js?<?= $cache ?>"></script>
		<script type="text/javascript" src="js/index.js?<?= $cache ?>"></script>
		
		<script type="text/javascript" src="plugins/bootstrap/js/bootstrap.min.js?<?= $cache ?>"></script>
		
		<script>
			SITE.data = {
				client_rules_url: '<?= CLIENT_RULES_URL ?>',
				field_options_url: '<?= FIELD_OPTIONS_URL ?>',
				client_id: '<?= $config['SpectrumEMPClientID'] ?>',
			};
		</script>
	</body>
</html>