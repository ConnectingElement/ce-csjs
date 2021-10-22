<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Action_Custom
 */
final class NF_Actions_Custom_CE_CSJS extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'Subscribe to CSJS';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
     * @var string
     */
    protected $_timing = 'normal';

    /**
     * @var int
     */
    protected $_priority = 10;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Subscribe to CSJS', 'ce-capi' );

        $settings = apply_filters( 'ninja_forms_action_custom_ce_csjs_settings', array(
            'field_id_title'   => array(
                'name'           => 'field_id_title',
                'type'           => 'field-select',
                'group'          => 'primary',
                'label'          => esc_html__( 'Select field containing user\'s title'),
                'value'          => esc_html__( 'The field in the form that contains the user\'s title'),
                'width'          => 'full',
                'use_merge_tags' => true,
            ),
            'field_id_forename'   => array(
                'name'           => 'field_id_forename',
                'type'           => 'field-select',
                'group'          => 'primary',
                'label'          => esc_html__( 'Select field containing user\'s forename'),
                'value'          => esc_html__( 'The field in the form that contains the user\'s forename'),
                'width'          => 'full',
                'use_merge_tags' => true,
            ),
            'field_id_surname'   => array(
                'name'           => 'field_id_surname',
                'type'           => 'field-select',
                'group'          => 'primary',
                'label'          => esc_html__( 'Select field containing user\'s surname'),
                'value'          => esc_html__( 'The field in the form that contains the user\'s surname'),
                'width'          => 'full',
                'use_merge_tags' => true,
            ),
            'field_id_email'   => array(
                'name'           => 'field_id_email',
                'type'           => 'field-select',
                'group'          => 'primary',
                'label'          => esc_html__( 'Select field containing user\'s E-mail address'),
                'value'          => esc_html__( 'The field in the form that contains the user\'s E-mail address'),
                'width'          => 'full',
                'use_merge_tags' => true,
            ),
            'csjs_source'   => array(
                'name'           => 'csjs_source',
                'type'           => 'textbox',
                'group'          => 'primary',
                'label'          => esc_html__( 'Source'),
                'value'          => esc_html__( 'Website Signup Form'),
                'width'          => 'full',
                'use_merge_tags' => true,
            ),
        ) );
		$this->_settings = array_merge( $this->_settings, $settings );
    }
    
    public function save( $action_settings )
    {
    }

    public function process( $action_settings, $form_id, $data )
    {
        require_once(WP_PLUGIN_DIR . '/ce-csjs/classes/CSJSConsumer.class.php');
        
		
        /*
         * Validate
         */
        $errors = [];
        if (!$action_settings['field_id_title']) {
            $errors[] = 'The title field is not valid.';
        }
        if (!$action_settings['field_id_forename']) {
            $errors[] = 'The forename field is not valid.';
        }
        if (!$action_settings['field_id_surname']) {
            $errors[] = 'The surname field is not valid.';
        }
        if (!$action_settings['field_id_email']) {
            $errors[] = 'The email field is not valid.';
        }
        if (!$action_settings['csjs_source']) {
            $errors[] = 'The source setting is not valid.';
        }
        
        if ($errors) {
            require_once(WP_PLUGIN_DIR . '/ce-csjs/includes/ce-csjs.php');
            CE_CSJS::notify_admin($errors);
            $data['errors']['form'][] = 'There was a problem subscribing you, please try again later.';
        } else {
            /*
            Carry out our processing using the settings here
            */
            $options = get_option('ce-csjs');

            $subscriber = [
                'title'         => $action_settings['field_id_title'],
                'forename'      => $action_settings['field_id_forename'],
                'surname'       => $action_settings['field_id_surname'],
                'email'         => $action_settings['field_id_email'],
                'source'        => $action_settings['csjs_source'],
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
                $data['errors']['form'][] = 'There was a problem subscribing you, please try again later.';
            }
        }


        return $data;
    }
}
