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
class NF_Action_MyAction extends NF_Notification_Base_Type
{
	/**
	 * Get things rolling
	 */
	function __construct() {
		$this->name = __( 'My Cool Action' );
	}
	/**
	 * Output our edit screen
	 *
	 * @access public
	 * @since 2.8
	 * @return void
	 */
	public function edit_screen( $id = '' )
	{
		/* 
		This is how we get setting values to output them into our settings page.
		notification is an old naming convention. Eventually it will be changed to action.
		*/
		$my_setting = Ninja_Forms()->notification( $id )->get_setting( 'my_setting' );
		/*
		By default, settings are output into a table. We need to wrap our settings in <tr> and <td> tags.
		This lets all of our settings within the action page to be similar.
		
		The most important thing to keep in mind is the naming convention for your settings: settings[setting_name]
		This will allow Ninja Forms to save the setting for you.
		*/
		?>
		<tr>
			<th scope="row"><label for="settings-my_setting"><?php _e( 'My Setting' ); ?></label></th>
			<td><input type="text" name="settings[my_setting]" id="settings-my_setting" value="<?php echo esc_attr( $my_setting ); ?>" class="regular-text"/></td>
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
	public function process( $id ) {
		/*
		We declare our $ninja_forms_processing global so that we can access submitted values.
		*/
		global $ninja_forms_processing;
		
		/*
		Get our setting
		*/
		$my_setting = Ninja_Forms()->notification( $id )->get_setting( 'my_setting' );
		
		/*
		Carry out our processing using the setting here
		*/
		
	}
}
return new NF_Action_MyAction();