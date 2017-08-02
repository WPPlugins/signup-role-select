<?php

class SRS_Main{

    function __construct() {
        add_action( 'user_register', array( $this, 'srs_save_custome_field' ) );
        add_action( 'register_form', array( $this, 'srs_show_user_roles' ) );
        add_filter( 'registration_errors', array( $this, 'srs_custom_error', 10, 3 ) );
    }


    // Add role selection field in register form
    public function srs_show_user_roles() {

        $roles = srs_get_option('srs_st_roles', 'srs_st_basics');

        $mode = srs_get_option('srs_st_mode', 'srs_st_basics', 'select');
        $default = srs_get_option('srs_st_default_role', 'srs_st_basics', 'subscriber');
        if ( $roles ) : 
            if ( 'select' == $mode ) : ?>
                <p>
                    <label for="user-role"><?php _e( 'Select Your role:', 'signup-role-select' ); ?></label><br>
                    <select name="srs-user-role" id="user-role" class="input">
                        <?php foreach ( $roles as $role_name ) : ?>
                            <option value="<?php echo strtolower($role_name); ?>" <?php if ( strtolower($role_name) == $default ) echo 'selected'; ?>><?php echo srs_get_option('srs_st_role_' . $role_name, 'srs_st_custom_names', $role_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            <?php else : ?>
                <p>
                    <label for="user-role"><?php _e( 'Select Your role:', 'signup-role-select' ); ?></label><br>
                    <?php foreach ( $roles as $role_name ) : ?>
                        <input type="radio" name="srs-user-role" value="<?php echo $role_name ?>" <?php if ( strtolower($role_name) == $default ) echo 'checked'; ?>><span class="srs-radio-text"><?php echo srs_get_option('srs_st_role_' . $role_name, 'srs_st_custom_names', $role_name); ?></span><br>
                    <?php endforeach; ?>
                </p>
            <?php endif;
        endif;
    }


    // Add error if role is not selected or admin role is selected even if allowed in settings
    public function srs_custom_error( $errors, $sanitized_user_login, $user_email ) {
        
        if ( empty( $_POST['srs-user-role'] ) || ! empty( $_POST['srs-user-role'] ) && trim( $_POST['srs-user-role'] ) == '' || 'administrator' == $_POST['srs-user-role'] ) {
            $errors->add( 'srs_user_register_role_error', __('<strong>ERROR</strong>: An error occurred while setting Your role. Please contact with site administrator.', 'signup-role-select') ); 
        }

        return $errors;
    }


    // Save role and update user role
    public function srs_save_custome_field( $user_id ) {
        if ( isset( $_POST['srs-user-role'] ) && ! empty($_POST['srs-user-role']) && 'Administrator' == $_POST['srs-user-role'] ) {
            $roles = srs_get_option('srs_st_roles','srs_st_basics');
            if ( in_array( $_POST['srs-user-role'] , $roles) ) {
                $user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['srs-user-role'] ) );
            }
        }
    }


}