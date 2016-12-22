<?php
/**
 * Custom Fields
 */


function empformembed_add_meta_box() {

  //global $post;

  //if ( get_post_type( $post->ID ) == 'empformembed_type' ) {
    add_meta_box(
      'empformembed_meta_box', // $id
      'EMP Form Settings', // $title 
      'empformembed_show_meta_box', // $callback
      'empformembed_type', // $page
      'normal', // $context
      'high'); // $priority
  //}
}
add_action('add_meta_boxes', 'empformembed_add_meta_box');

// Meta Field Array
$prefix = 'empformembed_';
$custom_fields = array(     
    
    array(
      'label' => 'API Key',
      'desc'  => 'Enter the API Key for the EMP form.',
      'id'    => $prefix.'api_key',
      'type'  => 'text',
    ),

    array(
      'label' => 'Client ID',
      'desc'  => 'Enter the Client ID for the EMP form.',
      'id'    => $prefix.'client_id',
      'type'  => 'text',
    ),

    array(
      'label' => 'Display "Thank You" page in a new window',
      'id'    => $prefix.'new_tab',
      'type'  => 'checkbox',
    )
);

// Inline Content Callback
function empformembed_show_meta_box() {
	global $custom_fields, $post;
	// Use nonce for verification
	echo '<input type="hidden" name="empformembed_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
     
    // Begin the field table and loop
    echo '<table class="form-table">';

    // Display the EMP form shortcode
    if (get_post_type($post->ID) == 'empformembed_type') {
    echo '<tr>
            <th>Shortcode</th>
            <td>[empformembed pid="'.$post->ID.'"]</td>
          </tr>';
    }
    //echo '<tr><th>Shortcode</th><td>[inline_content post_id='.$post->ID.']</td></tr>';
    foreach ($custom_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
                switch($field['type']) {
                    // case items will go here
                    // text
					case 'text':
					    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
					        <br /><span class="description">'.$field['desc'].'</span>';
					break;

					// textarea
					case 'textarea':
					    echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
					        <br /><span class="description">'.$field['desc'].'</span>';
					break;

					// checkbox
					case 'checkbox':
					    echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
					        <label for="'.$field['id'].'">'.$field['desc'].'</label>';
					break;

					// select
					case 'select':
					    echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
					    foreach ($field['options'] as $option) {
					        echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
					    }
					    echo '</select><br /><span class="description">'.$field['desc'].'</span>';
					break;
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function empformembed_save_custom_meta($post_id) {
    global $custom_fields;
     
    // verify nonce
    if (!wp_verify_nonce($_POST['empformembed_meta_box_nonce'], basename(__FILE__))) 
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
     
    // loop through fields and save the data
    foreach ($custom_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}
add_action('save_post', 'empformembed_save_custom_meta');
