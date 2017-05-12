<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Action_Custom
 */
final class NF_Actions_Custom_CE_CSJS extends NF_Abstracts_ActionNewsletter
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
    
    protected $_setting_labels = array(
        'list'   => 'List',
        'fields' => 'List Field Mapping',
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Subscribe to CSJS', 'ce-capi' );
    }
    
    public function get_lists()
    {
        $options = get_option('ce-csjs');
        
        return [
            [
                'value'     => $options['mailing_list_id'],
                'label'     => 'CSJS Mailing List ID ' . $options['mailing_list_id'],
                'fields'    => [
                    [
                        'value' => 'field_id_title',
                        'label' => __( 'Ninja Forms Field ID for Title', 'ce-csjs' )
                    ],
                    [
                        'value' => 'field_id_forename',
                        'label' => __( 'Ninja Forms Field ID for Forename', 'ce-csjs' )
                    ],
                    [
                        'value' => 'field_id_surname',
                        'label' => __( 'Ninja Forms Field ID for Surname', 'ce-csjs' )
                    ],
                    [
                        'value' => 'field_id_email',
                        'label' => __( 'Ninja Forms Field ID for Email', 'ce-csjs' )
                    ],
                ]
            ]
        ];
    }

    public function save( $action_settings )
    {
        error_log('save was called with: ' . var_export($action_settings, true));
    }

    public function process( $action_settings, $form_id, $data )
    {
        error_log('process called with args ' . var_export(func_get_args(), true));

        return $data;
    }
}
