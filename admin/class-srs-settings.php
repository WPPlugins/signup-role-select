<?php

/**
 * WordPress SRS Settings demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('SRS_Settings' ) ):
class SRS_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new SRS_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( __('Signup Roles', 'signup-role-select'), __('Signup Roles', 'signup-role-select'), 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'srs_st_basics',
                'title' => __( 'Basic Settings', 'signup-role-select' )
            ),
            array(
                'id' => 'srs_st_custom_names',
                'title' => __( 'Custome role names', 'signup-role-select' )
            )
        );
        return $sections;
    }


    private function srs_get_all_roles() {

        $a = array();
        $roles = get_editable_roles();

        foreach ( $roles as $role_name => $role_info ) {
            $a[$role_name] = $role_info['name'];
        }
        if ( isset($a['administrator']) ) {
            unset($a['administrator']);
        }
        return $a;

    }


    private function srs_role_trnslations(){
        $roles = $this->srs_get_all_roles();
        foreach ( $roles as $role_name => $role_info ) {
            $s['name'] = 'srs_st_role_' . $role_name;
            $s['label'] = $role_info;
            $s['type'] = 'text';
            $s['default'] = $role_info;
            $setting[] = $s;
        }
        return $setting;
    } 

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'srs_st_basics' => array(
                array(
                    'name'              => 'srs_st_roles',
                    'label'             => __( 'Select Roles', 'signup-role-select' ),
                    'desc'              => __( 'Select roles to show in registration form', 'signup-role-select' ),
                    'type'              => 'multicheck',
                    'options'           => $this->srs_get_all_roles()
                ),
                array(
                    'name'              => 'srs_st_default_role',
                    'label'             => __( 'Default role to select', 'signup-role-select' ),
                    'type'              => 'select',
                    'options'           => $this->srs_get_all_roles(),
                    'default'           => 'subscriber'
                ),
                array(
                    'name'              => 'srs_st_mode',
                    'label'             => __( 'Select Roles', 'signup-role-select' ),
                    'desc'              => __( 'You can show available roles in register form as select box or radio group.', 'signup-role-select' ),
                    'type'              => 'radio',
                    'options'           => array(
                        'select' => __( 'Select Box', 'signup-role-select' ),
                        'radio' => __( 'Radio', 'signup-role-select' )
                        ),
                    'default' => 'select'
                )
            ),
            'srs_st_custom_names' => $this->srs_role_trnslations()
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}

endif;


