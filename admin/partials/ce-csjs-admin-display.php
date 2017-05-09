<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.connectingelement.co.uk
 * @since      1.0.0
 *
 * @package    CE_CSJS
 * @subpackage CE_CSJS/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    
    <form method="post" name="ce-csjs_options" action="options.php">
        <?php 
            settings_fields($this->plugin_name); 
            $options = get_option($this->plugin_name);
        ?>
        <!-- Account ID -->
        <fieldset>
            <legend class="screen-reader-text"><span>Account ID</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-account_id">
                <span><?php esc_attr_e('Account ID', $this->plugin_name); ?></span>
            </label>
            <input type="text" id="<?php echo $this->plugin_name; ?>-account_id" name="<?php echo $this->plugin_name; ?>[account_id]" value="<?php print($options['account_id']); ?>"  class="regular-text"/>
        </fieldset>
        
        <!-- Mailing List ID -->
        <fieldset>
            <legend class="screen-reader-text"><span>Mailing List ID</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-mailing_list_id">
                <span><?php esc_attr_e('Mailing List ID', $this->plugin_name); ?></span>
            </label>
            <input type="text" id="<?php echo $this->plugin_name; ?>-mailing_list_id" name="<?php echo $this->plugin_name; ?>[mailing_list_id]" value="<?php print($options['mailing_list_id']); ?>"  class="regular-text"/>
        </fieldset>
        
        <!-- Source -->
        <fieldset>
            <legend class="screen-reader-text"><span>Source</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-source">
                <span><?php esc_attr_e('Source', $this->plugin_name); ?></span>
            </label>
            <input type="text" id="<?php echo $this->plugin_name; ?>-source" name="<?php echo $this->plugin_name; ?>[source]" value="<?php print($options['source']); ?>"  class="regular-text"/>
        </fieldset>
        
        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>

</div>
