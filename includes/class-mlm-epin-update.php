<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Epin_Update {
	use letscms_mlmfunction;
	public function letscms_mlmePinUpdate(){
        $mlm_settings = get_option('letscms_mlm_general_settings');

    if (isset($mlm_settings['binarymlm_ePin_activate']) && $mlm_settings['binarymlm_ePin_activate'] == '1') {
        global $wpdb;
        global $current_user;
        $user_id = $current_user->ID;
        $user = get_userdata($user_id);
        $user_key = $wpdb->get_var("select user_key from {$wpdb->prefix}binarymlm_users where user_id='{$user->ID}'");
        /* check that it is mlm user or not */
        $res = $wpdb->get_row("SELECT epin_no FROM {$wpdb->prefix}binarymlm_epins WHERE user_key = '" . $user_key . "'");
        $adminajax= "'".admin_url('admin-ajax.php')."'";

        if ($wpdb->num_rows > 0) {

            $payment_status = $wpdb->get_var("select payment_status from {$wpdb->prefix}binarymlm_users where user_id='{$user->ID}'");
            if ($payment_status == '1') {
                echo '<div class="notibar msgsuccess"><p>' . __('Your status is already to set to <b>Paid</b> in the system. You cannot activate your membership again with an ePin.', 'mlm') . '</p></div>';
            }
            else if ($payment_status == '2') {
                echo '<div class="notibar msgsuccess"><p>' . __('Your status is already set to <b>Active</b> in the system. You cannot activate your membership again with an ePin.', 'mlm') . '</p></div>';
            }
        }
        else {
            $not_mlm = $wpdb->get_row("select id from {$wpdb->prefix}binarymlm_users where user_id='{$user->ID}'");
            if ($wpdb->num_rows == '0') {
                $this->letscms_check_user();
            }
            else {
                $payment_status = $wpdb->get_var("select payment_status from {$wpdb->prefix}binarymlm_users where user_id='{$user->ID}'");
                if ($payment_status == '1') {
                    echo '<div class="notibar msgsuccess"><p>' . __('Your status is already to set to <b>Paid</b> in the system. You cannot activate your membership again with an ePin.', 'mlm') . '</p></div>';
                }
                else if ($payment_status == '2') {
                   echo '<div class="notibar msgsuccess"><p>' . __('Your status is already set to <b>Active</b> in the system. You cannot activate your membership again with an ePin.', 'mlm') . '</p></div>';
                }
                else {
                    $epin = '<input type="text" name="epin" id="epin_' . $user_id . '" onBlur="checkePinAvailability('.$adminajax.',this.value);"><br/><div id="check_epin"></div><input type="button" value="Update ePin" id="update_' . $user_id . '" onclick="setePinUser('. $adminajax. ',' . $user_id . ',document.getElementById(\'epin_' . $user_id . '\').value);">
                    <span id="epinmsg_' . $user_id . '"></span>';
                    return $epin;
                }
            }
        }
    }
    else {
         echo '<div class="notibar msgerror"><p>' . __('Sorry. You are not allowed to access this page due to administrative permissions.', 'mlm') . '</p></div>';
    }
	}//end function
}//end class
