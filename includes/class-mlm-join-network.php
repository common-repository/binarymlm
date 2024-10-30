<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Join_Network {
	use letscms_mlmfunction;
	public function letscms_join_network(){
	global $wpdb, $current_user;
    $user_id = $current_user->ID;
    $error = array();
    $chk = 'error';
    $mlm_general_settings = get_option('letscms_mlm_general_settings');
    
    //most outer if condition
    if (isset($_POST['submit'])) {
        $sponsor = sanitize_text_field($_POST['sponsor']);
        $epin = !empty($_POST['epin']) ? $_POST['epin'] : '';
        if (empty($sponsor)) {
            $sponsor = $wpdb->get_var("select `username` FROM {$wpdb->prefix}binarymlm_users order by id asc limit 1");
        }
        if (!empty($epin) && $this->letscms_epin_exists($epin)) {
            $error[] = "ePin already issued or wrong ePin.";
        }
        if (!empty($mlm_general_settings['sol_payment']) && empty($epin)) {
            $error[] = "Please enter your ePin.";
        }
        
        //Add usernames we don't want used
        $invalid_usernames = array('admin');
        //Do username validation
        $sql = "SELECT COUNT(*) num, `user_key` FROM {$wpdb->prefix}binarymlm_users WHERE `username` = '$sponsor'";
        $intro = $wpdb->get_row($sql);

        if (isset($_GET['l']) && $_GET['l'] != '') {
            $leg = $_GET['l'];
        }
        else {
            //error_reporting(0);
            $leg = $_POST['leg'];
        }

        if (isset($leg) && $leg != '0') {
            if ($leg != '1') {
                $error[] = "You have enter a wrong placement.";
            }
        }

        if (!isset($leg)) {
            $key = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE user_id='$user_id'");
            $l = $this->letscms_totalLeftLegUsers($key);
            $r = $this->letscms_totalRightLegUsers($key);
            if ($l < $r) {
                $leg = '0';
            }
            else {
                $leg = '1';
            }
        }
        //generate random numeric key for new user registration
        $user_key = $this->letscms_generateKey(); 
        //if generated key is already exist in the DB then again re-generate key
        do {
            $check = $wpdb->get_var("SELECT COUNT(*) ck FROM {$wpdb->prefix}binarymlm_users WHERE `user_key` = '$user_key'");
            $flag = 1;
            if ($check == 1) {
                $user_key = $this->letscms_generateKey();
                $flag = 0;
            }
        } while ($flag == 0);

        //check parent key exist or not

        if (isset($_GET['k']) && $_GET['k'] != '') {
            if (!$this->letscms_checkKey($_GET['k'])) {
                $error[] = "Parent key does't exist.";
            }
            // check if the user can be added at the current position
            $checkallow = $this->letscms_checkallowed($_GET['k'], $leg);
            if ($checkallow >= 1) {
                $error[] = "You have enter a wrong placement.";
            }
        }
        // outer if condition
        if (empty($error)) {

            // inner if condition
            if ($intro->num == 1) {
                $sponsor = $intro->user_key;

                $sponsor1 = $sponsor;
                //find parent key
                if (isset($_GET['k']) && $_GET['k'] != '') {
                    $parent_key = $_GET['k'];
                }
                else {
                    $readonly_sponsor = '';
                    do {
                        $sql = "SELECT `user_key` FROM {$wpdb->prefix}binarymlm_users 
                                WHERE parent_key = '" . $sponsor1 . "' AND 
                                leg = '" . $leg . "' AND banned = '0'";
                        $spon = $wpdb->get_var($sql);
                        $num = $wpdb->num_rows;
                        if ($num) {
                            $sponsor1 = $spon;
                        }
                    } while ($num == 1);
                    $parent_key = $sponsor1;
                }

                // return the wp_users table inserted user's ID
                $user = array
                    (
                    'ID' => $user_id,
                    'role' => 'binarymlm_user'
                );

                // return the wp_users table inserted user's ID
                $user_id = wp_update_user($user);
                $username = $current_user->user_login; 

                if (!empty($epin)) {
                    $pointResult = $wpdb->get_row("select p_id,point_status from {$wpdb->prefix}binarymlm_epins where epin_no = '{$epin}'");
                    $pointStatus = $pointResult->point_status;
                    $product_price = $wpdb->get_var("SELECT product_price FROM {$wpdb->prefix}binarymlm_product_price WHERE p_id = '" . $pointResult->p_id . "'");
                    // to epin point status 1 
                    if ($pointStatus[0] == '1') {
                        $paymentStatus = '1';
                        $payment_date = current_time('mysql');
                    }
                    // to epin point status 1 
                    else if ($pointStatus[0] == '0') {
                        $paymentStatus = '2';
                        $payment_date = current_time('mysql');
                    }
                }
                
                $insert = "INSERT INTO {$wpdb->prefix}binarymlm_users(user_id, username, user_key, parent_key, sponsor_key, leg,payment_status,product_price) VALUES('" . $user_id . "','" . $username . "', '$user_key', '$parent_key', '" . $sponsor . "', '" . $leg . "','" . $paymentStatus . "','" . $product_price . "')";

                // if all data successfully inserted
                if ($wpdb->query($insert)) { //begin most inner if condition
                    //entry on Left and Right Leg tables
                    if ($leg == 0) {
                        $insert = "INSERT INTO {$wpdb->prefix}binarymlm_leftleg  (pkey, ukey) VALUES ('$parent_key','$user_key')";
                        $insert = $wpdb->query($insert);
                    }
                    else if ($leg == 1) {
                        $insert = "INSERT INTO {$wpdb->prefix}binarymlm_rightleg(pkey, ukey) VALUES('$parent_key','$user_key')";
                        $insert = $wpdb->query($insert);
                    }
                    //begin while loop
                    while ($parent_key != '0') {
                        $query = "SELECT COUNT(*) num, parent_key, leg 
                                FROM {$wpdb->prefix}binarymlm_users 
                                WHERE user_key = '$parent_key'
                                AND banned = '0'";
                        $result = $wpdb->get_row($query);
                        if ($result->num == 1) {
                            if ($result->parent_key != '0') {
                                if ($result->leg == 1) {
                                    $tbright = "INSERT INTO {$wpdb->prefix}binarymlm_rightleg (pkey,ukey) 
						VALUES('" . $result->parent_key . "','$user_key')";
                                    $tbright = $wpdb->query($tbright);
                                }
                                else {
                                    $tbleft = "INSERT INTO {$wpdb->prefix}binarymlm_leftleg (pkey, ukey) 
						VALUES('" . $result->parent_key . "','$user_key')";
                                    $tbleft = $wpdb->query($tbleft);
                                }
                            }
                            $parent_key = $result->parent_key;
                        }
                        else {
                            $parent_key = '0';
                        }
                    }//end while loop
                    if (isset($epin) && !empty($epin)) {
                        $sql = "update {$wpdb->prefix}binarymlm_epins set user_key='{$user_key}', date_used='" . current_time('mysql') . "', status=1 where epin_no ='{$epin}' ";
                        $wpdb->query($sql);
                    }
                    if ($paymentStatus == 1) {
                        $this->insert_refferal_commision($user_id);
                    }
                    $chk = '';
                    $msg= "<div class='notibar msgsuccess'><p>Congratulations! You have successfully Join MLM</p></div>";
                }//end most inner if condition
            } //end inner if condition
            else {
                $error = "\n Sponsor does not exist in the system.";
            }
        }//end outer if condition
    }//end most outer if condition
    //if any error occoured
    if (!empty($error)) {
        foreach($error as $er){
             echo $er."</br>";
            }
    }
    if ($chk != '') {
        ?>
        <?php
        if ($current_user->roles[0] == 'binarymlm_user') {
            echo '<div class="notibar msgsuccess"><p>Dear <b>' .$current_user->user_login. ' </b>&nbsp;, You are already a MLM user.</p></div>';
        }
        else {
          ?>
            <script>
                function checkspname() {
                    var spname = document.getElementById('sponsor').value;
                    if (spname == '') {
                        if (!confirm('Are you sure you do not know your Sponsor\'s username? Proceed without a Sponsor?')) {
                            return false;
                        }
                    }
                }
            </script>

            <?php if (isset($msg) && $msg != "") echo $msg; ?>
            <form name="frm" method="post" action="" onSubmit="return checkspname()">			
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr><td colspan="2">&nbsp;</td></tr>

                    <tr>
                        <td><?php _e('Sponsor Name', 'mlm'); ?> <span style="color:red;">*</span> :</td>
                        <td><?php $adminajax= "'".admin_url('admin-ajax.php')."'"; ?>
                            <input type="text" name="sponsor" id="sponsor" value="<?php isset($_POST['sponsor'])?htmlentities($_POST['sponsor']):''?>" maxlength="20" size="37" onBlur="checkReferrerAvailability(<?php echo $adminajax;?>,this.value);">
                            <br /><div id="check_referrer"></div>
                        </td>
                    </tr>
					
					<?php
                    if (!empty($mlm_general_settings['ePin_activate']) && !empty($mlm_general_settings['sol_payment'])) {
                    $adminajax= "'".admin_url('admin-ajax.php')."'";
                    ?>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                        <td><?php _e('Enter ePin', 'mlm'); ?><span style="color:red;">*</span> :</td>
                        <td><input type="text" name="epin" id="epin" value="<?php isset($_POST['epin'])?htmlentities($_POST['epin']):''?>" maxlength="20" size="37" onBlur="checkePinAvailability(<?php echo $adminajax;?>,this.value);"><br /><div id="check_epin"></div></td>
                    </tr>
                <?php } else if (!empty($mlm_general_settings['ePin_activate'])) { 
                $adminajax= "'".admin_url('admin-ajax.php')."'";
                    ?>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                        <td><?php _e('Enter ePin', 'mlm'); ?> :</td>
                        <td><input type="text" name="epin" id="epin" value="<?php isset($_POST['epin'])?htmlentities($_POST['epin']):''?>" maxlength="20" size="37" onBlur="checkePinAvailability(<?php echo $adminajax;?>,this.value);"><br /><div id="check_epin"></div></td>
                    </tr>
                    <?php
                }
                ?>
                <tr><td colspan="2">&nbsp;</td></tr>
                    
                    <tr>
                        <td><?php _e('Placement', 'mlm'); ?> <span style="color:red;">*</span> :</td>
                        <td><?= __('Left', 'mlm') ?> <input id="left" type="radio" name="leg" value="0" <?= (isset($leg) && $leg == '0') ? 'checked="checked"' : ''; ?> />
                            <?= __('Right', 'mlm') ?><input id="right" type="radio" name="leg" value="1" <?= (isset($leg) && $leg == '1') ? 'checked="checked"' : ''; ?>/>
                        </td>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                        <td colspan="2"><input type="submit" name="submit" id="submit" value="<?php _e('Submit', 'mlm') ?>" /></td>
                    </tr>
                </table>
            </form>
            <?php
        }
        ?>	
        <?php
    }
    else {
        _e($msg);
    }
	}//end function
}//end class
