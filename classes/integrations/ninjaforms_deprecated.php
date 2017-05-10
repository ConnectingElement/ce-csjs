<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Class for our custom action type.
 *
 * @package     Ninja Forms
 * @subpackage  Classes/Actions
 * @copyright   Copyright (c) 2014, WPNINJAS
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.8
*/
// I know this says NF_Notification_Base_Type, but the name will eventually be changed to reflect the action nomenclature.
class NF_Action_Custom_CE_CSJS extends NF_Notification_Base_Type
{
	/**
	 * Get things rolling
	 */
	function __construct() 
    {
		$this->name = __( 'Subscribe to CSJS' );
	}
    
	/**
	 * Output our edit screen
	 *
	 * @access public
	 * @since 2.8
	 * @return void
	 */
	public function edit_screen($id = '')
	{
		/* 
		This is how we get setting values to output them into our settings page.
		notification is an old naming convention. Eventually it will be changed to action.
		*/
		$field_id_title     = Ninja_Forms()->notification($id)->get_setting('field_id_title');
        $field_id_forename  = Ninja_Forms()->notification($id)->get_setting('field_id_forename');
        $field_id_surname   = Ninja_Forms()->notification($id)->get_setting('field_id_surname');
        $field_id_email     = Ninja_Forms()->notification($id)->get_setting('field_id_email');
        $source             = Ninja_Forms()->notification($id)->get_setting('source');
		/*
		By default, settings are output into a table. We need to wrap our settings in <tr> and <td> tags.
		This lets all of our settings within the action page to be similar.
		
		The most important thing to keep in mind is the naming convention for your settings: settings[setting_name]
		This will allow Ninja Forms to save the setting for you.
		*/
        ?>
		<tr>
			<th scope="row"><label for="settings-field_id_title"><?php _e( 'Ninja Forms Field ID for Title' ); ?></label></th>
			<td><input type="text" name="settings[field_id_title]" id="settings-field_id_title" value="<?php echo esc_attr( $field_id_title ); ?>" class="regular-text"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-field_id_forename"><?php _e( 'Ninja Forms Field ID for Forename' ); ?></label></th>
			<td><input type="text" name="settings[field_id_forename]" id="settings-my_setting" value="<?php echo esc_attr( $field_id_forename ); ?>" class="regular-text"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-field_id_surname"><?php _e( 'Ninja Forms Field ID for Surname' ); ?></label></th>
			<td><input type="text" name="settings[field_id_surname]" id="settings-field_id_surname" value="<?php echo esc_attr( $field_id_surname ); ?>" class="regular-text"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-field_id_email"><?php _e( 'Ninja Forms Field ID for Email' ); ?></label></th>
			<td><input type="text" name="settings[field_id_email]" id="settings-field_id_email" value="<?php echo esc_attr( $field_id_email ); ?>" class="regular-text"/></td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-source"><?php _e( 'Source name to send to CSJS' ); ?></label></th>
			<td><input type="text" name="settings[source]" id="settings-source" value="<?php echo esc_attr( $source ); ?>" class="regular-text"/></td>
		</tr>
		<?php
	}
    
	/**
	 * Process our Redirect notification
	 *
	 * @access public
	 * @since 2.8
	 * @return void
	 */
	public function process($id) 
    {
		/*
		We declare our $ninja_forms_processing global so that we can access submitted values.
		*/
		global $ninja_forms_processing;
		
        require_once(WP_PLUGIN_DIR . '/ce-csjs/classes/CSJSConsumer.class.php');
        
		/*
		Get our settings
		*/
		$field_id_title     = Ninja_Forms()->notification($id)->get_setting('field_id_title');
        $field_id_forename  = Ninja_Forms()->notification($id)->get_setting('field_id_forename');
        $field_id_surname   = Ninja_Forms()->notification($id)->get_setting('field_id_surname');
        $field_id_email     = Ninja_Forms()->notification($id)->get_setting('field_id_email');
        $source             = Ninja_Forms()->notification($id)->get_setting('source');
		
        /*
         * Validate
         */
        $errors = [];
        if (!$field_id_title) {
            $errors[] = 'The NinjaForms field ID for the title field is not valid.';
        }
        if (!$field_id_forename) {
            $errors[] = 'The NinjaForms field ID for the forename field is not valid.';
        }
        if (!$field_id_surname) {
            $errors[] = 'The NinjaForms field ID for the surname field is not valid.';
        }
        if (!$field_id_email) {
            $errors[] = 'The NinjaForms field ID for the email field is not valid.';
        }
        if (!$source) {
            $errors[] = 'The NinjaForms source setting is not valid.';
        }
        
        if ($errors) {
            require_once(WP_PLUGIN_DIR . '/ce-csjs/includes/ce-csjs.php');
            CE_CSJS::notify_admin($errors);
            $ninja_forms_processing->add_error('csjs_error', 'There was a problem subscribing you, please try again later.');
        } else {
            /*
            Carry out our processing using the settings here
            */
            $options = get_option('ce-csjs');

            $subscriber = [
                'title'         => $ninja_forms_processing->get_field_value($field_id_title),
                'forename'      => $ninja_forms_processing->get_field_value($field_id_forename),
                'surname'       => $ninja_forms_processing->get_field_value($field_id_surname),
                'email'         => $ninja_forms_processing->get_field_value($field_id_email),
                'source'        => $options['source'],
                'mailing_lists' => [
                    [
                        'mailinglistid'     => $options['mailing_list_id'],
                        'action'            => CSJSConsumer::MAILING_LIST_ACTION_SUBSCRIBE
                    ]
                ]
            ];        

            $csjs = new CSJSConsumer($options['username'], $options['password']);
            $response = $csjs->subscribe([$subscriber]);

            if ($response && CSJSConsumer::responseSuccess($response)) {
                return;
            } else {
                error_log('Could not subscribe user; CSJS response was: ' . var_export($response, true));
                $ninja_forms_processing->add_error('csjs_error', 'There was a problem subscribing you, please try again later.');
            }
        }
	}
}
return new NF_Action_Custom_CE_CSJS();