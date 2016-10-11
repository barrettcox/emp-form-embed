SITE.field_rules_form_library = (function($) {
	
    var urls = {};
    
    return {
        
        fields_by_id : {},
        manipulate_fields : true,
        field_rules : {},
        $form : {},
            
        setup_library : function(client_id, manipulate_fields) {
            var self = this;
            urls.GET_FIELD_RULES = SITE.data.client_rules_url + '?CLIENT-ID=' + SITE.data.client_id;
            urls.POST_FIELD_CHANGE = SITE.data.field_options_url + '';
            self.new_field_subscribe_setup();
        },
        
        _submit_field_update : function _submit_field_update(post_data) {
            post_data += '&CLIENT-ID=' + SITE.data.client_id;
            var self = this;
            $.post(urls.POST_FIELD_CHANGE, post_data, function(data) {
                if (data.status == 'success') {
					 $.each(data.data, function(field_id, field_action) {
						
						// Let's broadcast the events for whoever wants to listen
						if ( self.fields_by_id.hasOwnProperty(field_id) ) {
    						field_action['field_id'] = field_id;
    						field_action['field'] = self.fields_by_id[field_id];
    						field_action['form'] = self.$form;
    						field_action['self'] = self;
    						$.publish('spectrumEMP/formFieldAction', field_action);
                        } else {
                            // this field targeted is not present on this form
                            $.publish('spectrumEMP/formFieldAction', field_action);
                        }
                        
					 });
				} else {
					return false;
				}
            }, 'json');  
        },
        
        new_field_subscribe_setup : function(){
        	var self = this;
        	$.subscribe('spectrumEMP/formFieldUpdate', function(e, field_data) {
	        	self.fields_by_id[field_data.field_id] = field_data.field;
	        	$.each(self.fields_by_id, function(watch_field, submit_fields) {
		        	$(self.fields_by_id[watch_field]).unbind('change');
	        	});
	        	self._setup_listeners();
        })},
        
        _setup_listeners : function() {
            var self = this;
            $.each(self.field_rules, function(watch_field, submit_fields) {
                if (self.fields_by_id.hasOwnProperty(watch_field)) {
                    $(self.fields_by_id[watch_field]).on('change', function(e) {
                        var post_obj = '',
                        	$field;
                        
                        $.each(submit_fields, function(key, field_id) {
                           if (field_id in self.fields_by_id) {
                                $field = $(self.fields_by_id[field_id]);
                                
                                if ($field.hasClass('dob-field')) {
                                	var dob_date = [];
                                	
	                                $.each($field, function() {
		                                if ($(this).val() != '') {
			                                dob_date.push($(this).val());
			                            }
	                                });
	                                
	                                if (dob_date.length == 3) {
		                                if (post_obj != '') {
			                                post_obj += '&';
		                                }
		                                
		                                post_obj += field_id + '=' + dob_date[0] + '/' + dob_date[1] + '/' + dob_date[2];
		                            }
		                        // post YYYY-mm-dd values as mm/dd/YYYY
		                        } else if ($field.hasClass('iqs-form-date')) {
		                        	var date = $field.val().split('-');
		                        	
		                        	if (date.length == 3) {
		                                if (post_obj != '') {
			                                post_obj += '&';
		                                }
		                                
		                                post_obj += field_id + '=' + date[1] + '/' + date[2] + '/' + date[0];
		                            }
                                } else {
	                                if (post_obj != '') {
		                                post_obj += '&';
	                                }
	                                
	                                if ($(self.fields_by_id[field_id]).serialize() !== '') {
										post_obj += $(self.fields_by_id[field_id]).serialize();
									} else {
										post_obj += field_id + '=';
									}
	                            }
                           } 
                        });
                        
                        if (post_obj != '') {
	                        self._submit_field_update(post_obj);
	                    }
                    });
                }
            });
        },
        
        register_fields : function(fields_by_id, $form) {
            var self = this;
            self.$form = $form;
            self.fields_by_id = fields_by_id;
            $.get(urls.GET_FIELD_RULES, function(data) {
                if (data.status == 'success') {
                    self.field_rules = data.data;
                    self._setup_listeners();
                    $.each(self.field_rules, function(watch_field, submit_fields) {
				    	$(self.fields_by_id[watch_field]).first().trigger('change');
				    });
                } else {
                    return false;
                }
            }, 'json');
        }
       
    }

}(jQuery));