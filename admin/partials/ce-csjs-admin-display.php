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
        <table class="form-table">
            <tbody>
                <!-- Account ID -->
                <tr>
                    <th scope="row">
                        <legend class="screen-reader-text"><span>Account ID</span></legend>
                        <label for="<?php echo $this->plugin_name; ?>-account_id">
                            <span><?php esc_attr_e('Account ID', $this->plugin_name); ?></span>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="<?php echo $this->plugin_name; ?>-account_id" name="<?php echo $this->plugin_name; ?>[account_id]" value="<?php print($options['account_id']); ?>"  class="regular-text"/>
                    </td>
                </tr>
                <!-- Username -->
                <tr>
                    <th scope="row">
                        <legend class="screen-reader-text"><span>Username</span></legend>
                        <label for="<?php echo $this->plugin_name; ?>-username">
                            <span><?php esc_attr_e('Username', $this->plugin_name); ?></span>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="<?php echo $this->plugin_name; ?>-username" name="<?php echo $this->plugin_name; ?>[username]" value="<?php print($options['username']); ?>"  class="regular-text"/>
                    </td>
                </tr>
                <!-- Password -->
                <tr>
                    <th scope="row">
                        <legend class="screen-reader-text"><span>Password</span></legend>
                        <label for="<?php echo $this->plugin_name; ?>-password">
                            <span><?php esc_attr_e('Password', $this->plugin_name); ?></span>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="<?php echo $this->plugin_name; ?>-password" name="<?php echo $this->plugin_name; ?>[password]" value="<?php print($options['password']); ?>"  class="regular-text"/>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>

</div>
