<?php

require_once('config.php');

/*
 * api functions
 */

function getFormFromSpectrumEMPAPI($spectrum_emp_api_key) {
	$html = '';
	$modals = array();
	$phone_fields = array();
	
	$ch = curl_init(REQUIREMENTS_URL . '?IQS-API-KEY=' . $spectrum_emp_api_key);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$raw_html = curl_exec($ch);
	$curl_errno = curl_errno($ch);
	$curl_error = curl_error($ch);
	curl_close($ch);
	
	if ($curl_errno > 0) {
		$html = '
			<div class="alert alert-danger">
				Error ' . $curl_errno . ', please try again.
			</div>
		';
		
		return array('html' => $html, 'modals' => $modals);
	}
	
	$json = json_decode($raw_html);
	
	if (!isset($json->data) || !isset($json->data->sections)) {
		$html = '
			<div class="alert alert-danger">
				Error, please try again.
			</div>
		';
		
		return array('html' => $html, 'modals' => $modals);
	}
	
	//$html .= '<form id="form_example" action="scripts/formhandler.php" method="post">';
	$html .= '<form id="form_example" action="'.plugins_url().'/emp-for-wordpress/includes/emp/scripts/formhandler.php" method="post">';
	
	foreach ($json->data->sections as $section_index => $section) {
		$html .= '
			<div class="section">
				<h3 class="page-header">' . $section->name . ' <small>' . $section->description . '</small></h3>
		';
		
		foreach ($section->fields as $field_index => $field) {
			$label = $field->displayName;
			
			if ($field->id == 6) { // Address Line 1
				$label = 'Address';
			} else if ($field->id == 7) { // Address Line 2
				$label = '';
			}
			
			if ($field->htmlElement == 'input-text') {
				$class = '';
				
				if (stripos($field->description, 'phone number') !== false) {
					$class = ' iqs-form-phone-number';
					$phone_fields[] = $field->id;
				} else {
					$class = ' iqs-form-text';
				}
				
				$html .= '
					<div class="row">
						<div class="form-group">
							<label for="' . $field->id . '" class="col-sm-4 control-label">' . $label . (($field->required) ? ' <span class="asterisk">*</span>' : '') . '</label>
							<div class="col-sm-6 col-md-5">
								<input type="text"
									name="' . $field->id . '"
									id="' . $field->id . '"
									class="form-control' . (($field->required) ? ' required' : '') . $class . '" placeholder="' . $field->displayName . '" />
				';
				
				if ($class == ' iqs-form-phone-number' && isset($section->fields[$field_index + 1])
					&& ($section->fields[$field_index]->order + 0.1) == $section->fields[$field_index + 1]->order) {
						
						$element_id = $section->fields[$field_index + 1]->id;
						$label_text = trim($section->fields[$field_index + 1]->displayName);
						$opt_in_text = '<a href="#text-message-opt-in-modal" class="blue" data-toggle="modal">opt-in policy</a>';
						$label_text = str_ireplace('opt-in policy', $opt_in_text, $label_text);
						
						$modals[] = '
							<div id="text-message-opt-in-modal" class="modal fade">
						    	<div class="modal-dialog">
							    	<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title">Text Message Opt-in Policy</h4>
										</div>
										<div class="modal-body">
											 ' . $section->fields[$field_index + 1]->helpText . '
										</div>
										<div class="modal-footer">
											<button type="button" class="btn" data-dismiss="modal">Close</button>
										</div>
							    	</div>
						    	</div>
							</div>
						';
						
						$html .= '
							<input type="checkbox" name="' . $element_id . '" id="' . $element_id . '">
							<label id="label-' . $element_id . '" for="' . $element_id . '">' . $label_text . '</label>
						';
				}
				
				if ($field->helpText !== '') {
					$html .= '<p class="help-block">' . $field->helpText . '</p>';
				}
				
				$html .= '
							</div>
						</div><!-- end class="form-group" -->
					</div><!-- end class="row" -->
				';
				
			} else if ($field->htmlElement == 'select') {
				$class = ' iqs-form-single-select';
				
				$html .= '
					<div class="row">
						<div class="form-group">
							<label for="' . $field->id . '" class="col-sm-4 control-label">' . $label . (($field->required) ? ' <span class="asterisk">*</span>' : '') . '</label>
							<div class="col-sm-6 col-md-5">
								<select
									name="' . $field->id . '"
									id="' . $field->id . '"
									class="input-sm form-control' . (($field->required) ? ' required' : '') . $class . '">
									<option value=""></option>
				';
				
				if ($field->id == 9) { // State
					$html .= '<option value="Outside US & Canada">Outside US & Canada</option>';
				}
				
				foreach ($field->options as $option) {
					if (isset($option->options)) {
						$html .= '<optgroup label="' . $option->label . '">';
						
						foreach ($option->options as $sub_option) {
							$html .= '<option value="' . $sub_option->id . '">' . $sub_option->value . '</option>';
						}
						
						$html .= '</optgroup>';
						
					} else {
						$html .= '<option value="' . $option->id . '">' . $option->value . '</option>';
					}
				}
				
				$html .= '
								</select>
								' . (($field->helpText !== '') ? '<p class="help-block">' . $field->helpText . '</p>' : '') . '
							</div>
						</div><!-- end class="form-group" -->
					</div><!-- end class="row" -->
				';
			}
		}
		
		$html .= '
			</div><!-- end class="section" -->
		';
	}
	
	$html .= '
		<div class="clear"></div>
		
		<br />
		
		<div class="alert alert-success form-submit-success"></div>
		
		<div class="alert alert-danger form-submit-danger"></div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Go <i class="icon-chevron-right icon-white"></i></button>
		</div>
		
		<input type="hidden" id="form_submit" name="form_submit" value="form_example" />
		<input type="hidden" id="phone_fields" name="phone_fields" value="' . implode(',', $phone_fields) . '" />
		
		<div class="clear"></div>
		
		<br />
	';
	
	$html .= '</form>';
	
	return array('html' => $html, 'modals' => $modals);
}

?>