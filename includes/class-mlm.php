<?php
/**
* Binary MLM setup
*
* @package Binary MLM
*/

defined( 'ABSPATH' ) || exit;
/**
 * Main Letscms_mlmClass.
 *
 * @class Letscms_mlm
 */

final class Letscms_mlm{
	
	use letscms_mlmfunction;
	
	protected static $_instance = null;
	
	/**
	 * Main Binary MLM Instance.
	 *
	 * Ensures only one instance of Letscms_mlm is loaded or can be loaded.
	 */
	public static function instance() { 
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
	}

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
		$this->define_shortcodes();
		//$this->cron_schedule();
	}

		public function letscms_mlm_cron_job( $schedules ) {
		    $schedules['min'] = array(
		            'interval'  => 30,
		            'display'   => __( 'Every 3 Minutes', 'mlm' ),
		    );
		    print_r($schedules); die;
		    return $schedules;
		}


		/**
		 * Define MLM Constants.
		 */
		private function define_constants() {

			$upload_dir = wp_upload_dir( null, false );
			// Path and URL
			$this->define("MLM_ABSPATH", dirname( MLM_PLUGIN_FILE ) . '/' );
			$this->define("MLM_PLUGIN_DIR", WP_PLUGIN_DIR . "/binarymlm" );

			$this->define("MLM_PLUGIN_NAME", "binarymlm");
			$this->define("MENU_NAME", "MLM Menu");
			
			$this->define("LETSCMS_MLM_REGISTRATION_TITLE", "BinaryMLM Register New User");
			$this->define("LETSCMS_MLM_REGISTRATION_SHORTCODE", "binarymlm-user-register");

			$this->define("LETSCMS_MLM_NETWORK_TITLE", "BinaryMLM Network");
			$this->define("LETSCMS_MLM_NETWORK_SHORTCODE", "binarymlm-binary-network");

			$this->define("LETSCMS_MLM_GENEALOGY_TITLE", "BinaryMLM Genealogy");
			$this->define("LETSCMS_MLM_GENEALOGY_SHORTCODE", "binarymlm-binary-network");

			$this->define("LETSCMS_MLM_NETWORK_DETAILS_TITLE", "BinaryMLM Network Details");
			$this->define("LETSCMS_MLM_NETWORK_DETAILS_SHORTCODE", "binarymlm-network-details");

			$this->define("LETSCMS_MLM_LEFT_GROUP_DETAILS_TITLE", "BinaryMLM Left Group");
			$this->define("LETSCMS_MLM_LEFT_GROUP_DETAILS_SHORTCODE", "binarymlm-left-group");

			$this->define("LETSCMS_MLM_RIGHT_GROUP_DETAILS_TITLE", "BinaryMLM Right Group");
			$this->define("LETSCMS_MLM_RIGHT_GROUP_DETAILS_SHORTCODE", "binarymlm-right-group");

			$this->define("LETSCMS_MLM_PERSONAL_GROUP_DETAILS_TITLE", "BinaryMLM Personal Group");
			$this->define("LETSCMS_MLM_PERSONAL_GROUP_DETAILS_SHORTCODE", "binarymlm-personal-group");

			$this->define("LETSCMS_MLM_MY_CONSULTANT_TITLE", "BinaryMLM My Consultants");
			$this->define("LETSCMS_MLM_MY_CONSULTANT_SHORTCODE", "binarymlm-consultant-group");

			$this->define("LETSCMS_MLM_MY_PAYOUTS", "BinaryMLM My Payouts");
			$this->define("LETSCMS_MLM_MY_PAYOUTS_SHORTCODE", "binarymlm-payouts");

			$this->define("LETSCMS_MLM_MY_PAYOUT_DETAILS", "BinaryMLM My Payouts Details");
			$this->define("LETSCMS_MLM_MY_PAYOUT_DETAILS_SHORTCODE", "binarymlm-payout-details");

			$this->define("LETSCMS_MLM_UPDATE_PROFILE_TITLE", "BinaryMLM Update Profile");
			$this->define("LETSCMS_MLM_UPDATE_PROFILE_SHORTCODE", "binarymlm-update-profile");

			$this->define("LETSCMS_MLM_CHANGE_PASSWORD_TITLE", "BinaryMLM Change Password");
			$this->define("LETSCMS_MLM_CHANGE_PASSWORD_SHORTCODE", "binarymlm-change-pass");
		
			$this->define("LETSCMS_MLM_DISTRIBUTE_COMMISSION_TITLE", "BinaryMLM Distribute Commission");
			$this->define("LETSCMS_MLM_DISTRIBUTE_COMMISSION_SHORTCODE", "binarymlm-distribute-commission");

			$this->define("LETSCMS_MLM_DISTRIBUTE_BONUS_TITLE", "BinaryMLM Distribute Bonus");
			$this->define("LETSCMS_MLM_DISTRIBUTE_BONUS_SHORTCODE", "binarymlm-distribute-bonus");

			//upgrade code start
			$this->define("LETSCMS_MLM_UPGRADE_TITLE", "BinaryMLM Upgrade Payment");
			$this->define("LETSCMS_MLM_UPGRADE_SHORTCODE", "binarymlm-upgrade");

			//ein and join network
			$this->define("LETSCMS_MLM_EPIN_UPDATE_TITLE", "BinaryMLM Epin Update");
			$this->define("LETSCMS_MLM_EPIN_UPDATE_SHORTCODE", "binarymlm-epin-update");

			$this->define("LETSCMS_MLM_JOIN_NETWORK", "BinaryMLM Join Network");
			$this->define("LETSCMS_MLM_JOIN_NETWORK_SHORTCODE", "binarymlm-join-network");
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}



		/**
		* Include required core files used in admin and on the frontend.
		*/
		public function includes() {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			require_once(ABSPATH.'wp-admin/includes/user.php');

		 	
 			include_once MLM_ABSPATH . 'class.php';
  			include_once MLM_ABSPATH . 'includes/class-mlm-install.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-menu.php';
			include_once MLM_ABSPATH . 'includes/admin/mlm-dashboard.php';
			include_once MLM_ABSPATH . 'includes/admin/description.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-register-first-user.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-general-setting.php';
  			include_once MLM_ABSPATH . 'includes/admin/mlm-eligibility-setting.php';
			include_once MLM_ABSPATH . 'includes/admin/mlm-payout-setting.php';
 		    include_once MLM_ABSPATH . 'includes/admin/mlm-bonus-setting.php';
  			include_once MLM_ABSPATH . 'includes/admin/mlm-email-template.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-epin-settings.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-product-price.php';
 			include_once MLM_ABSPATH . 'includes/admin/paypal-detail-page.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-reset-all-data.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-run-payouts.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-payout-reports.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-view-network.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-user-report.php';
 			include_once MLM_ABSPATH . 'includes/admin/admin-reports.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-epin-reports.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-epin-list-table.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-pending-withdrawal.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-withdrawal-process.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-withdrawal-success.php';
 			include_once MLM_ABSPATH . 'includes/admin/mlm-withdrawal-list-table.php';

 			include_once MLM_ABSPATH . 'includes/cron_scheduling/mlm-cron-scheduling.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-registration.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-genealogy.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-update-profile.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-change-password.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-my-consultant.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-my-left-group.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-my-right-group.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-personal-group.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-network-details.php';
			include_once MLM_ABSPATH . 'includes/class-mlm-my-payout.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-my-payout-details.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-epin-update.php';
 			include_once MLM_ABSPATH . 'includes/class-mlm-join-network.php';

		}

	 

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0
	 */
		private function init_hooks() {
			
		add_role( 'binarymlm_user', __('Binary MLM Users' ), array() );
		load_plugin_textdomain('mlm', false, MLM_PLUGIN_DIR . '/lang/');
		add_action('init', array($this,'register_my_session'));
		register_activation_hook( MLM_PLUGIN_FILE, array( $this, 'activate' ));
		add_action('upgrader_process_complete', array($this,'mlm_plugin_update'));
		add_filter('wp_get_nav_menu_items', array($this,'pages_filter_nav_items'));
		register_deactivation_hook( MLM_PLUGIN_FILE, array( $this, 'deactivate' ));
		add_action('admin_enqueue_scripts', array($this,'admin_enqueue_script') );
		add_filter('manage_users_columns', array($this,'add_custom_column'));
	    add_action('manage_users_custom_column',  array($this,'add_custom_column_value'), 10, 3);
		add_action('wp_ajax_binarymlm_paymentstatus', array($this,'update_payment_status_action') );
		add_action('wp_ajax_binarymlm_checkepin', array($this,'check_epin_action'));
		add_action('wp_ajax_nopriv_binarymlm_checkepin', array($this,'check_epin_action'));
		add_action('wp_ajax_binarymlm_epinupdate', array($this,'update_epin_action'));
		add_action('wp_ajax_nopriv_binarymlm_epinupdate', array($this,'update_epin_action'));
		add_action('wp_ajax_nopriv_binarymlm_checksponsoravail', array($this,'check_sponsor_avail_action'));
		add_action('wp_ajax_binarymlm_resetdata', array($this,'reset_all_data'));

      	add_action( 'wp_ajax_withdrawal_delete', array($this,'withdrawal_delete') );
      	add_action( 'wp_ajax_nopriv_withdrawal_delete', array($this,'withdrawal_delete') );
      
	    add_action( 'wp_ajax_withdrawal_init', array($this,'withdrawal_init') );
      	add_action( 'wp_ajax_nopriv_withdrawal_init', array($this,'withdrawal_init') );
	}

		/* To egister session */
		public function register_my_session(){ 
		    if( ! session_id() ) {
		        session_start();
		    }
		}

		/* To activate the plugin */
		public function activate()
		{ 
			$install_class=new Letscms_MLM_Install();
			$install_class->install();
		}

		/* To update the plugin*/
		public function mlm_plugin_update()
		{ 
			$install_class=new Letscms_MLM_Install();
			$install_class->update();
		}

		/* To add menu */
		public function pages_filter_nav_items($items)
		{ 
	    	foreach($items as $item) {
	        $allowed  = $this->menuAllowed($item->object_id);
	        if($allowed)
				{
					$filtered[] = $item;
				}
	    	}
	    return $filtered;
		}

		public function menuAllowed($post_id){
			global $wpdb,$current_user;
			if(is_admin()) return true;
			$is_mlm_page=get_post_meta($post_id,'is_mlm_page',true);
			if($is_mlm_page){
			if ( is_user_logged_in() &&  in_array( 'binarymlm_user', $current_user->roles ) ){
				return true;
			} else {
				return false;
				}

			} else {
				return true;
				
			}

		}

		/* To deactivate the plugin */
		public function deactivate()
		{
			$install_class=new Letscms_MLM_Install();
			$install_class->deactivate();
		}

		/* To include js*/
		public function admin_enqueue_script() {
			wp_enqueue_script('mlm-payment-update', MLM_URL . '/includes/js/ajax.js');
			wp_enqueue_script('mlm-custom', MLM_URL . '/includes/js/custom.js',array(),null,true);
		}

		/* To add a custom column in user's list */
		public function add_custom_column( $columns ){
			$columns['parent_name'] = __('Parent', 'mlm');
		    $columns['position'] = __('Position', 'mlm');
		    $columns['sponsor_name'] = __('Sponsor', 'mlm');
		    $columns['payment_status'] = __('Payment Status', 'mlm');
		    
		    $mlm_settings = get_option('letscms_mlm_general_settings');
		    if ($mlm_settings['binarymlm_ePin_activate'] == 1)
		    {
		    	$columns['ePin'] = __('ePin', 'mlm'); 
			}
		    
		    return $columns;

		}

		/* To update payment status value */
		public function update_payment_status_action(){
			$this->payment_status_change_ajax();
			$this->insert_refferal_commision();
			echo $output = !empty($_REQUEST['status']) ? $_REQUEST['name'] : '';
			die();
		}

		/* To update ePin value */
		public function check_epin_action(){
			$this->letscms_checkepin();
			die();
		}

		/* To update ePin value */
		public function update_epin_action(){
			$this->letscms_update_epin();
			die();
		}

		/* To check the sponsor name availability*/
		public function check_sponsor_avail_action(){
			$this->letscms_sponsor_name_availabilty();
			die();
		}
		/* To Reset all data of plugin */
		public function reset_all_data(){
			$this->letscms_mlm_reset_data();
			die();
		}

		public function withdrawal_delete()
		{
			$user_id = $_POST['withdrawaldel_id'];
		    $pay_id = $_POST['payoutid'];
		    $sql = "UPDATE {$wpdb->prefix}binarymlm_payout SET `withdrawal_initiated` = '0', `withdrawal_initiated_comment` = '', `withdrawal_initiated_date` = '0000-00-00' WHERE `user_id` = '" . $user_id . "' AND `payout_id`='" . $pay_id . "'";
		    $wpdb->query($sql);
		}

		public function withdrawal_init(){
			if (isset($_POST['memberid'])) { 
			    $memberId = $_POST['memberid'];
			    $pay_id = $_POST['payoutid'];
			    $comment = $_POST['comment_withdrawal'];
			    $amount = $_POST['total_amt'];
			    $sql = "UPDATE {$wpdb->prefix}binarymlm_payout SET `withdrawal_initiated`=1, `withdrawal_initiated_comment` = '" . $comment . "', `withdrawal_initiated_date` = NOW() WHERE `user_id` = '" . $memberId . "' AND `payout_id`='" . $pay_id . "'";
			    $wpdb->query($sql);
			    if ($u = get_option('letscms_mlm_withdrawal_mail', true) == 1) {
			        $this->WithDrawalProcessedMail($memberId, $comment, $wamount);
			    }
			}

		}

		/* To add a value in custom columns added */
		public function add_custom_column_value( $value, $column_name, $user_id ){
		global $wpdb;
		$mlm_settings = get_option('letscms_mlm_general_settings');
		/***************************/

		if ( 'payment_status' == $column_name)
		{
			/*check that it is mlm_user or not */
			$sql="SELECT user_id, payment_status,special FROM {$wpdb->prefix}binarymlm_users WHERE user_id = $user_id";
			$res = $wpdb->get_row($sql);
			$html = '';

			if($wpdb->num_rows > 0)
			{
				$currStatus = $res->payment_status;
				$special=$res->special;
				$adminajax= "'".admin_url('admin-ajax.php')."'";
				global $paymentStatusArr;
				$paymentStatusArr = array(0=>'Unpaid', 1=>'Paid');
				if (isset($mlm_settings['binarymlm_ePin_activate']) && $mlm_settings['binarymlm_ePin_activate'] == '1') {
    			$paymentStatusArr[2] = 'Free Pin';
				}

				$html .= '<select name="payment_status_'.$user_id.'" id="payment_status_'.$user_id.'" onchange="update_payment_status('.$adminajax.','.$user_id.','.$special.',this.value)">';
				foreach($paymentStatusArr AS $row=>$val) {
				if($row == $currStatus ){ $sel = 'selected="selected"'; }
				else { $sel =''; }
				$html .= '<option value="'.$row.'" '.$sel.'>'.$val.'</option>';
				}
				$html .= '</select><span id="resultmsg_'.$user_id.'"></span>';
				//echo $html;
				return $html;
				} else{
					return "Not a MLM User";
				}
		} //payment status column value added

		if ('ePin' == $column_name) {
		        global $wpdb;
		        $user = get_userdata($user_id);
		        $user_key = $wpdb->get_var("select user_key from {$wpdb->prefix}binarymlm_users where user_id='{$user->ID}'");
		        /* check that it is mlm user or not */
		        $res = $wpdb->get_row("SELECT epin_no FROM {$wpdb->prefix}binarymlm_epins WHERE user_key = '$user_key'");
		        $adminajax= "'".admin_url('admin-ajax.php')."'";

		        if ($wpdb->num_rows > 0) {
		            return $res->epin_no;
		        }
		        else {
		            $not_mlm = $wpdb->get_row("select id from {$wpdb->prefix}binarymlm_users where user_id='{$user->ID}'");
		            if ($wpdb->num_rows == '0') {
		                return 'Not MLM User';
		            }
		            else {
		                $payment_status = $wpdb->get_var("select payment_status from {$wpdb->prefix}binarymlm_users where user_id='{$user->ID}'");
		                if ($payment_status == '1') {
		                    return __(' ', 'mlm');
		                }
		                else if ($payment_status == '2') {
		                    return __(' ', 'mlm');
		                }
		                else {
		                    $epin = '<input type="text" name="epin" id="epin_' . $user_id . '"><input type="button" value="Update ePin" id="update_' . $user_id . '" onclick="setePinUser('.$adminajax.',' . $user_id . ',document.getElementById(\'epin_' . $user_id . '\').value);"><span id="epinmsg_' . $user_id . '"></span>';
		                    return $epin;
		                }
		            }
		        }
		    }//epin column value added


	    if ('parent_name' == $column_name) {
	        global $wpdb;

	        $res = $wpdb->get_row("SELECT user_id, payment_status FROM {$wpdb->prefix}binarymlm_users WHERE user_id = '$user_id'");
	        if ($wpdb->num_rows > 0) {
	            $user = get_userdata($user_id);
	            $parent_key = $this->letscms_getparentkeybyid($user->ID);
	            if ($parent_key != '0') {
	                $parent_name = $this->letscms_getusernamebykey($parent_key);
	                return $parent_name;
	            }
	            else {
	                return __(' ', 'mlm');
	            }
	        }
	        else {
	            return __(' ', 'mlm');
	        }
	    }//parent column value added

		if ('sponsor_name' == $column_name) {
		    global $wpdb;

		    $res = $wpdb->get_row("SELECT user_id, payment_status FROM {$wpdb->prefix}binarymlm_users WHERE user_id = '$user_id'");
		    if ($wpdb->num_rows > 0) {
		        $user = get_userdata($user_id);
		        $sponsor_key = $this->letscms_getsponsorkeybyid($user->ID);
		        if ($sponsor_key != '0') {
		            $sponsor_name = $this->letscms_getusernamebykey($sponsor_key);
		            return $sponsor_name;
		        }
		        else {
		            return __(' ', 'mlm');
		        }
		    }
		    else {
		        return __(' ', 'mlm');
		    }
		}//sponsor name column value added

		if ('position' == $column_name) {
		    global $wpdb;
		    $res = $wpdb->get_row("SELECT user_id, payment_status FROM {$wpdb->prefix}binarymlm_users WHERE user_id = '$user_id' AND parent_key!='0'");
		    if ($wpdb->num_rows > 0) {
		        $user = get_userdata($user_id);
		        $parent_key = $this->letscms_getparentkeybyid($user->ID);
		        $leg = $wpdb->get_var("SELECT leg FROM {$wpdb->prefix}binarymlm_users WHERE user_id = '$user->ID' "
		                . "AND parent_key='$parent_key' AND parent_key!='0'");
		        if ($leg == 1) {
		            $position = "Right";
		        }
		        elseif ($leg == 0) {
		            $position = "Left";
		        }

		        return $position;
		    }
			}//position column value added
	}


		/**
		 * Define MLM Shortcodes.
		 */
		private function define_shortcodes(){
			$MLM_Registration = new Letscms_MLM_Registration;
			add_shortcode(LETSCMS_MLM_REGISTRATION_SHORTCODE, array($MLM_Registration,'letscms_register_user'));
			$MLM_Genealogy = new Letscms_MLM_Genealogy;
			add_shortcode(LETSCMS_MLM_GENEALOGY_SHORTCODE, array($MLM_Genealogy,'letscms_genealogy_network'));
			$MLM_Update_Profile = new Letscms_MLM_Update_Profile;
			add_shortcode(LETSCMS_MLM_UPDATE_PROFILE_SHORTCODE, array($MLM_Update_Profile,'letscms_update_profile'));
			$MLM_Change_Password = new Letscms_MLM_Change_Password;
			add_shortcode(LETSCMS_MLM_CHANGE_PASSWORD_SHORTCODE, array($MLM_Change_Password,'letscms_change_password'));
			$MLM_My_Consultant = new Letscms_MLM_My_Consultant;
			add_shortcode(LETSCMS_MLM_MY_CONSULTANT_SHORTCODE, array($MLM_My_Consultant,'letscms_consultant_group'));
			$MLM_My_Left_Group = new Letscms_MLM_My_Left_Group;
			add_shortcode(LETSCMS_MLM_LEFT_GROUP_DETAILS_SHORTCODE, array($MLM_My_Left_Group,'letscms_left_group'));
			$MLM_My_Right_Group = new Letscms_MLM_My_Right_Group;
			add_shortcode(LETSCMS_MLM_RIGHT_GROUP_DETAILS_SHORTCODE, array($MLM_My_Right_Group,'letscms_right_group'));
			$MLM_My_Personal_Group = new Letscms_MLM_My_Personal_Group;
			add_shortcode(LETSCMS_MLM_PERSONAL_GROUP_DETAILS_SHORTCODE, array($MLM_My_Personal_Group,'letscms_personal_group'));
			$MLM_Network_Details = new Letscms_MLM_Network_Details;
			add_shortcode(LETSCMS_MLM_NETWORK_DETAILS_SHORTCODE, array($MLM_Network_Details,'letscms_network_details'));
			$MLM_My_Payout = new Letscms_MLM_My_Payout;
			add_shortcode(LETSCMS_MLM_MY_PAYOUTS_SHORTCODE, array($MLM_My_Payout,'letscms_my_payout'));
			$MLM_My_Payout_Details = new Letscms_MLM_My_Payout_Details;
			add_shortcode(LETSCMS_MLM_MY_PAYOUT_DETAILS_SHORTCODE, array($MLM_My_Payout_Details,'letscms_my_payout_details'));
			
			$MLM_Run_Payouts = new Letscms_MLM_Run_Payouts;
			add_shortcode(LETSCMS_MLM_DISTRIBUTE_COMMISSION_SHORTCODE, array($MLM_Run_Payouts,'letscms_mlmDistributeCommission'));
			add_shortcode(LETSCMS_MLM_DISTRIBUTE_BONUS_SHORTCODE, array($MLM_Run_Payouts,'letscms_mlmDistributeBonus'));

			$MLM_ePin_Update = new Letscms_MLM_Epin_Update;
			add_shortcode(LETSCMS_MLM_EPIN_UPDATE_SHORTCODE, array($MLM_ePin_Update,'letscms_mlmePinUpdate'));
			$MLM_Join_Network = new Letscms_MLM_Join_Network;
		    add_shortcode(LETSCMS_MLM_JOIN_NETWORK_SHORTCODE, array($MLM_Join_Network,'letscms_join_network'));
		}


}// End of class mlm
	