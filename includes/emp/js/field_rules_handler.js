SITE.field_rules_handler = (function($) {

    var CONTROL_GROUP_WRAPPER = '.form-group',
    	CONTROLS_WRAPPER = '.form-group div';

    $.subscribe('spectrumEMP/formFieldAction', function(e, field_action) {
		var $field = $(field_action['field']),
			$form = $(field_action['form']),
			has_changed = false;
		if (field_action.action == 'hide') {
			_hide_field($field);
		} else if (field_action.action == 'show') {
			_show_field($field);
		} else if (field_action.action == 'limit_options') {
			if ($field.is('select')) {
				// set specific options
				var option_html = '',
					temp_value = $field.val(),
					old_values = [],
					new_values = [],
					old_blank_value_text = '';
				
				$field.find('option').each(function() {
					if ($(this).val() != '') {
						old_values.push($(this).val());
					} else {
						old_blank_value_text = $(this).text();
					}
				});
				
				base_html = '';
				if (!($field.hasClass('iqs-form-multi-select'))) {
					base_html = '<option value="">' + old_blank_value_text + '</option>';
				}
				$.each(field_action.options, function(option_id, option_value) {
					if (option_id != '') {
						new_values.push(option_id);
					}
					if (option_value instanceof Object) {
						option_html += '<optgroup label="' + option_id + '">';
						$.each(option_value, function(sub_option_id, sub_option_value) {
							option_html += '<option value="' + sub_option_id + '">' + sub_option_value + '</option>';
						});
						option_html += '</optgroup>';
					} else {
						option_html += '<option value="' + option_id + '">' + option_value + '</option>';
					}
				});
				
				// if there are no options, do the same as hide
				if (option_html == '') {
					_hide_field($field);
				// if it just got some options, re-show it
				} else {
					_show_field($field);
				}
				
				if (old_values.sort().join() != new_values.sort().join()) {
					has_changed = true;
				}
				
				$field.html(base_html + option_html);
				$field.val(temp_value);
				
				if (has_changed) {
					$field.trigger('change');
				}
			} else if ($field.hasClass('iqs-form-checkbox') || $field.hasClass('iqs-form-radio')) {
				// set specific options
				var type = '',
					name = $field.attr('name'),
					option_html = '',
					checked_items = [],
					$field_items = $form.find('input[name="' + name + '"]'),
					$field_container = $field_items.parents(CONTROLS_WRAPPER),
					name_str = '',
					required_str = '',
					old_values = [],
					new_values = [];
				
				// checkboxes need to have a name like 658[] whereas radios can just be 658
				if ($field.hasClass('iqs-form-checkbox')) {
					type = 'checkbox';
					name_str = '[]';
				} else {
					type = 'radio';
				}
				
				$field_items.each(function() {
					if ($(this).val() != '') {
						old_values.push($(this).val());
					}
					if ($(this).attr('checked')) {
						checked_items.push($(this).val());
					}
					if ($(this).hasClass('required') || $(this).hasClass('required-hidden')) {
						required_str = 'required ';
					}
				});
				
				$.each(field_action.options, function(option_id, option_value) {
					if (option_id != '') {
						new_values.push(option_id);
					}
					option_html += '<label class="' + type + '">';
					option_html += '<input type="' + type + '" name="' + field_action.field_id + name_str + '" value="' + option_id + '"';
					if ($.inArray(option_id, checked_items) != -1) {
						option_html += ' checked';
					}
					option_html += ' class="' + required_str + 'iqs-form-' + type + '">' + option_value;
					option_html += '</label>';
				});
				
				// if there are no options, do the same as hide
				if (option_html == '') {
					required_str = 'required-hidden ';
					_hide_field($field_items);
				} else {
					_show_field($field_items);
				}
				
				base_html = '<input type="hidden" name="' + field_action.field_id + name_str + '" value="" class="' + required_str + 'iqs-form-' + type + '">';
				
				if (old_values.sort().join() != new_values.sort().join()) {
					has_changed = true;
				}
				
				$field_container.html(base_html + option_html);
				
				// resetup listeners
			    $.publish('spectrumEMP/formFieldUpdate', {field_id:field_action.field_id, field:$form.find('input[name="' + name + '"]')});
			    
			    if (has_changed) {
					$form.find('input[name="' + name + '"]').first().trigger('change');
				}
			}
		}
    });
    
    // hide the field
    function _hide_field($field) {
		$field.parents(CONTROL_GROUP_WRAPPER).hide();
		// temporarily hide any required class but be prepared to add it back if we have to reshow this field
		if ($field.hasClass('required')) {
			$field.removeClass('required').addClass('required-hidden');
		}
    }
    
    // show the field
    function _show_field($field) {
		$field.parents(CONTROL_GROUP_WRAPPER).show();
		// reshow any required fields that were previously hidden
		if ($field.hasClass('required-hidden')) {
			$field.removeClass('required-hidden').addClass('required');
		}
    }

}(jQuery))