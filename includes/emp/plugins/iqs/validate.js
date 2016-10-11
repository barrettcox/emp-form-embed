(function($) {

	$.fn.validate = function(customOptions) {
	
		var options = {
			onSuccess: null,
			onError: null,
			requiredClass: 'iqs-field-required',
			errorClass: 'iqs-field-error',
			errorApplyTo: 'fieldset',
			showFormat: false,
			formatClass: 'iqs-field-format',
			preventDefault: true
		};
		
		$.extend(options, customOptions);
		
		return this.each(function(index) {
			
			$(this).submit(function(e) {
				
				if (options.preventDefault) {
					// assume the call back will handle successful submission
					e.preventDefault();
				}
				
				$form = $(this);
				
				$form.find(options.errorApplyTo).removeClass(options.errorClass);
				$form.find('.' + options.formatClass).remove();
				
				$form.find(':input').each(function() {
					
					var $field = $(this),
						using = 'LR', // L = LENGTH, R = REGULAR EXPRESSION, C = CHECKED
						regex = /^/,
						msg = '';
					
					if ($field.hasClass(options.requiredClass) || ($field.attr('type') !== undefined && $field.attr('type').toLowerCase() != 'radio' && $field.attr('type').toLowerCase() != 'checkbox' && $field.val().length > 0)) {
						
						if ($field.hasClass('iqs-form-phone-number')) {
							regex = /^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$/;
							msg = '(999) 999-9999 ext. 9999';
						} else if ($field.hasClass('iqs-form-number') || $field.hasClass('iqs-form-gpa-four-point-scale')) {
							regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/;
							msg = '3.9';
						} else if ($field.hasClass('iqs-form-date')) {
							regex = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
							msg = '01/22/2011';
						} else if ($field.hasClass('iqs-form-url')) {
							regex = /^((http|https|ftp):\/\/)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
							msg = 'http://www.mywebsite.com';
						} else if ($field.hasClass('iqs-form-currency-us')) {
							regex = /^(\$)?(-)?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/;
							msg = '$00.00';
						} else if ($field.hasClass('iqs-form-email')) {
							regex = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
							msg = 'email@myemailaddress.com';
						} else if ($field.hasClass('iqs-form-sat-score')) {
							regex = /^\d{3}$/;
							msg = '650';
						} else if ($field.hasClass('iqs-form-act-score')) {
							regex = /^\d{2}$/;
							msg = '29';
						} else if ($field.hasClass('iqs-form-zip')) {
							using = 'L';
						} else if ($field.hasClass('iqs-form-textarea')) {
							using = 'L';
						} else if ($field.hasClass('iqs-form-text')) {
							using = 'L';
						} else if ($field.hasClass('iqs-form-single-select')) {
							using = 'L';
						} else if ($field.hasClass('iqs-form-file')) {
							using = 'L';
						} else if ($field.hasClass('iqs-form-password')) {
							using = 'L';
						} else if ($field.hasClass('iqs-form-checkbox')) {
							using = 'C';
						} else if ($field.hasClass('iqs-form-radio')) {
							using = 'C';
						} else if ($field.hasClass('iqs-form-multi-select')) {
							using = '';
						} else if ($field.hasClass('iqs-form-hidden')) {
							using = '';
						} else {
							using = '';
						}
						
						if (using.indexOf('L') != -1 && $field.val().length == 0) {
							$field.parents(options.errorApplyTo).addClass(options.errorClass);
						}
						
						if (using.indexOf('R') != -1 && !regex.test($field.val())) {
							$field.parents(options.errorApplyTo).addClass(options.errorClass);
						}
						
						if (using.indexOf('C') != -1 && $field.attr('name').length > 0 && !$form.find('[name="' + $field.attr('name') + '"]:input:checked').size()) {
							$field.parents(options.errorApplyTo).addClass(options.errorClass);
						}
						
						if (options.showFormat && msg.length > 0 && $field.parents(options.errorApplyTo).hasClass(options.errorClass)) {
							$('<div />').attr('class', options.formatClass).html(msg).appendTo($form).show().css('position', 'absolute').css({
								top: $field.offset().top,
								left: $field.offset().left + $field.width() + 10
							});
						}
					}
				});
				
				if ($form.find('.' + options.errorClass).length) {
					if (options.onError !== null) {
						return options.onError($form);
					}
				} else if (options.onSuccess !== null) {
					return options.onSuccess($form);
				}
			});
		});
	};
}(jQuery));
