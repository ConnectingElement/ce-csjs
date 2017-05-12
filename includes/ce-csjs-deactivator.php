<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.connectingelement.co.uk
 * @since      1.0.0
 *
 * @package    CE_CSJS
 * @subpackage CE_CSJS/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CE_CSJS
 * @subpackage CE_CSJS/includes
 * @author     Christopher Scarre <a@b.c>
 */
class CE_CSJS_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        /** @todo we need to deactivate any ninjaform actions that are using our action */
        
	}

}
