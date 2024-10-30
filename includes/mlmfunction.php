<?php
trait letscms_mlmfunction
{
function letscms_check_user($id=''){
	global $wpdb, $current_user;
	if($id=='')
		{
			$user_id=$current_user->ID; 
		} else{
			$user_id=$id;
		}
	$user_meta=get_userdata($user_id);
	$user_roles=$user_meta->roles; 
		
		if ( is_user_logged_in() ) {

				if ( ! empty( $user_roles ) && is_array( $user_roles ) && in_array( 'binarymlm_user', $user_roles ) || in_array('administrator', $user_roles)){
					return true;
				} else{	
				    $url = get_bloginfo('url'). "/join-network";
				?>
						<div class="notibar msgerror"><p>
						You are not a <b>MLM user</b>. To access this page , you must first register as MLM user by clicking on <a href='<?php echo $url; ?>' style='color:red;'>Join Network</a> or using <b>affiliate URL</b>. <br>Please contact the system admin at <?= get_settings('admin_email') ?> to report this problem.
						</p>
						</div>
					<?php die;
					}
		}
	else {
		$url = get_bloginfo('url')."/wp-login.php";
	    echo "<script>window.location='$url'</script>";
	}
}

function letscms_check_first_user(){ 
	global $wpdb;
	$sql = "SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_users "; 
	$num = $wpdb->get_var($sql);
	if($num==0){ 
		$url = get_bloginfo('url')."/wp-admin/admin.php?page=binarymlm-admin-settings&tab=createuser";
	    echo "<script>window.location='$url'</script>";
	}
}

function letscms_checkInputField($value)
{
	if($value=="")
		return true;
	else
		return false;
}

function letscms_confirmPassword($pass, $confirm)
{
	if($confirm != $pass)
		return true;
	else
		return false;
}

function letscms_confirmEmail($email, $confirm)
{
	if($confirm != $email)
		return true;
	else
		return false;
}

function letscms_epin_exists($value) {
    global $wpdb ;
    $epin = $wpdb->get_var("SELECT epin_no FROM {$wpdb->prefix}binarymlm_epins WHERE epin_no = '$value' AND status=0");
    if ($epin == "")
        return true;
    else
        return false;
}

//generate random key
function letscms_generateKey()
{
    // Random characters
	$characters = array("0","1","2","3","4","5","6","7","8","9");

	// set the array
	$keys = array();

	// set length
	$length = 9;

	// loop to generate random keys and assign to an array
	while(count($keys) < $length)
	{
		$x = mt_rand(0, count($characters)-1);
		if(!in_array($x, $keys))
       		$keys[] = $x;
	}

	// extract each key from array
	$random_chars='';
	foreach($keys as $key)
   		$random_chars .= $characters[$key];

	// display random key
	return $random_chars;
}

//generate random key
function letscms_generateThreeDigitKey()
{
    // Random characters
	$characters = array("0","1","2","3","4","5","6","7","8","9");

	// set the array
	$keys = array();

	// set length
	$length = 3;

	// loop to generate random keys and assign to an array
	while(count($keys) < $length)
	{
		$x = mt_rand(0, count($characters)-1);
		if(!in_array($x, $keys))
       		$keys[] = $x;
	}

	// extract each key from array
	$random_chars='';
	foreach($keys as $key)
   		$random_chars .= $characters[$key];

	// display random key
	return $random_chars;
}

function letscms_isMLMLic()
{
	$siteUrl = 'http';
	if ($_SERVER["HTTPS"] == "on") {$siteUrl .= "s";}
		$siteUrl .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$siteUrl .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$siteUrl .= $_SERVER["SERVER_NAME"];
	}

	$licKeyArr = get_option('letscms_mlm_license_settings');

	$packageName = MLM_PLUGIN_NAME;

	$params = 'k='.$licKeyArr['binarymlm_license_key'].'&d='.$siteUrl.'&p='.$packageName;
	$ch = curl_init('http://wordpressmlm.com/Auth/index.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

function letscms_licUpdate($licArr)
{
	update_option('letscms_mlm_license_settings', $licArr);

	if($this->letscms_isMLMLic()){
		return '<div class="notibar msgsuccess"><a class="close"></a><p>Your License key has been updated.</p></div>';
	}else{
		return '<div class="notibar msgalert"><a class="close"></a><p>Sorry, Your License key is invalid.</p></div>';
	}
}

function letscms_siteURL()
{
	$siteName = 'http';
  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {$siteName .= "s";}
	$siteName .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
  		$siteName .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$siteName .= $_SERVER["SERVER_NAME"];
	}
	return $siteName;
}

function letscms_checkPair($pair1, $pair2)
{
	if($pair1 == "" || $pair2 == "" || $pair1 == 0 || $pair2 == 0)
		return true;
	else
		return false;
}

function letscms_checkInitial($initial)
{
	if($initial == "" || $initial == 0)
		return true;
	else
		return false;
}

function letscms_getusernamebykey($key)
{
	global $wpdb;
	$id = $wpdb->get_var("SELECT username FROM {$wpdb->prefix}binarymlm_users	WHERE `user_key` = '".$key."'");
	return $id;
}

function letscms_getuserkeybyid($user_id) {
    global $wpdb;
    $key = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE user_id = '$user_id'");
    return $key;
}

function letscms_getparentkeybyid($user_id) {
    global $wpdb;
    $key = $wpdb->get_var("SELECT parent_key FROM {$wpdb->prefix}binarymlm_users WHERE user_id = '$user_id'");
    return $key;
}

function letscms_getsponsorkeybyid($user_id) {
    global $wpdb;
    $key = $wpdb->get_var("SELECT sponsor_key FROM {$wpdb->prefix}binarymlm_users WHERE user_id = '$user_id'");
    return $key;
}

function letscms_get_current_user_key()
{
	global $current_user, $wpdb;
	$username = $current_user->user_login;
	$user_key = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE username = '".$username."'");
	return $user_key;
}

function letscms_get_user_key_admin($username)
{
	global $wpdb;
	$user_key = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE username = '".$username."'");
	return $user_key;
}

function letscms_checkKey($key)
{
	global $wpdb;
	$user_key = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE `user_key` = '".$key."' AND banned = '0'");
  if(!$user_key){
			return false;
    } else {
	     return true;
  }
}

function letscms_checkallowed($key, $leg = NULL) {
    global $wpdb;
    $username = $wpdb->get_var("SELECT username FROM {$wpdb->prefix}binarymlm_users WHERE leg = '$leg' AND parent_key = '$key'");
    return $wpdb->num_rows;
}

//letscms_getUsername() function take the key and return the username of the user's key
function letscms_getUsername($key)
{
  global $wpdb;
	$sql = "SELECT username	FROM {$wpdb->prefix}binarymlm_users	WHERE user_key = '".$key."' AND banned = '0'";
	$username = $wpdb->get_var($sql);
	return $username;
}

// get_post_id function return the inserted post_id's
function letscms_get_post_id($page)
{
	global $wpdb;
	$sql = "SELECT id FROM {$wpdb->prefix}posts WHERE post_title = '".$page."'";
	$post_id = $wpdb->get_var($sql);
	return $post_id;
}

function letscms_get_post_id_or_postname($page)
{
	global $wpdb;
  $sql = "SELECT id FROM {$wpdb->prefix}posts WHERE post_title = '".$page."'";
  $post_id = $wpdb->get_var($sql);
	$mlm_settings = get_option('permalink_structure');
//	$sql = "SELECT post_id FROM {$wpdb->prefix}postmeta	WHERE meta_key = '".$page."' AND meta_value = '".$page."'";
//	$post_id = $wpdb->get_var($sql);
	if($mlm_settings == '/%postname%/')
	{
			$post_name = $wpdb->get_var("SELECT post_name FROM {$wpdb->prefix}posts WHERE id = $post_id");
			return bloginfo('url')."/".$post_name;
	}
	return bloginfo('url')."/?page_id=$post_id";
}

function letscms_totalLeftLegUsers($pkey)
{
	global $wpdb;
	$num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_leftleg	WHERE pkey = '".$pkey."'");
	return $num;
}

function letscms_totalRightLegUsers($pkey)
{
	global $wpdb;
	$num = $wpdb->get_var("SELECT COUNT(*) AS num	FROM {$wpdb->prefix}binarymlm_rightleg WHERE pkey = '".$pkey."'");
	return $num;
}

function letscms_totalSales($pkey)
{
	global $wpdb;
  $rightUsers=array();
  $leftUsers=array();
	$sql = "SELECT username, payment_status, user_key, sponsor_key, leg	FROM {$wpdb->prefix}binarymlm_users WHERE user_key IN
								(
								SELECT ukey	FROM {$wpdb->prefix}binarymlm_rightleg WHERE pkey = '".$pkey."'	ORDER BY id DESC
								)
								ORDER BY id DESC";
	$results = $wpdb->get_results($sql);
  $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$rightUsers[$i]['username'] = $data->username;
			$rightUsers[$i]['user_key'] = $data->user_key;
			$rightUsers[$i]['sponsor_key'] = $this->letscms_getSponsorName($data->sponsor_key);
			$rightUsers[$i]['leg'] = $this->letscms_legPlacement('1');
			$rightUsers[$i]['payment_status'] = $this->letscms_activeNotActive($data->payment_status);
			$i++;
		}
	}

	$sql = "SELECT username, payment_status, user_key, sponsor_key, leg FROM {$wpdb->prefix}binarymlm_users
								WHERE user_key IN
								(
								SELECT ukey FROM {$wpdb->prefix}binarymlm_leftleg WHERE pkey = '".$pkey."' ORDER BY id DESC
								)
								ORDER BY id DESC";

	$results = $wpdb->get_results($sql);
  $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$leftUsers[$i]['username'] = $data->username;
			$leftUsers[$i]['user_key'] = $data->user_key;
			$leftUsers[$i]['sponsor_key'] = $this->letscms_getSponsorName($data->sponsor_key);
			$leftUsers[$i]['leg'] = $this->letscms_legPlacement('0');
			$leftUsers[$i]['payment_status'] = $this->letscms_activeNotActive($data->payment_status);
      $i++;
		}
	}
		 if(count($leftUsers)!=0 || count($rightUsers)!=0)
 	 {
 	 				$consultant = array($leftUsers, $rightUsers);
 	 				return $consultant;
 	 }
 	 else
 	 {
 	 				$default[0]['username'] ='No Members Found';
 	 				$default[0]['payment_status']= '';
 	 				$consultant = array($default);
 	        return $consultant;
 	 }
}

function letscms_activeUsersOnLeftLeg($pkey)
{
  global $wpdb;
  $num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_users WHERE payment_status = '1' AND user_key IN
                                (
                                  SELECT ukey FROM {$wpdb->prefix}binarymlm_leftleg	WHERE pkey = '".$pkey."'
                                )");
  return $num;
}

function letscms_activeUsersOnRightLeg($pkey)
{
  global $wpdb;
  $num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_users WHERE payment_status = '1' AND user_key IN
                                        (
                                        SELECT ukey FROM {$wpdb->prefix}binarymlm_rightleg WHERE pkey = '".$pkey."'
                                        )");
  return $num;
}

function letscms_totalMyPersonalSales($sponsor)
{
  global $wpdb;
  $num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_users WHERE sponsor_key = '".$sponsor."'");
  return $num;
}

function letscms_activeUsersOnPersonalSales($sponsor)
{
global $wpdb;
$num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_users WHERE sponsor_key = '".$sponsor."' AND payment_status = '1'");
return $num;
}

function letscms_myFiveLeftLegUsers($pkey)
{
	global $wpdb;
	$sql = "SELECT username, payment_status	FROM {$wpdb->prefix}binarymlm_users	WHERE user_key IN
								(
								SELECT ukey FROM {$wpdb->prefix}binarymlm_leftleg	WHERE pkey = '".$pkey."' ORDER BY id DESC
								)
				  ORDER BY id DESC LIMIT 0,5";

	$results = $wpdb->get_results($sql);
  $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
		$users[$i]['username'] = $data->username;
		$users[$i]['payment_status'] = $this->letscms_activeNotActive($data->payment_status);
		$i++;
		}
	}
	else
	{
		$users[$i]['username'] = 'No Member Found';
		$users[$i]['payment_status'] = '';
	}
	return $users;
}


function letscms_myFiveRightLegUsers($pkey)
{
	global $wpdb;
	$sql = "SELECT username, payment_status FROM {$wpdb->prefix}binarymlm_users WHERE user_key IN
							(
							SELECT ukey FROM {$wpdb->prefix}binarymlm_rightleg	WHERE pkey = '".$pkey."'	ORDER BY id DESC
							)
							ORDER BY id DESC LIMIT 0,5";

	$results = $wpdb->get_results($sql);
  $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$users[$i]['username'] = $data->username;
			$users[$i]['payment_status'] = $this->letscms_activeNotActive($data->payment_status);
			$i++;
		}
	}
	else
	{
		$users[$i]['username'] = 'No Member Found';
		$users[$i]['payment_status'] = '';
	}
	return $users;
}

function letscms_myFivePersonalUsers($pkey)
{
	global $wpdb;
	$sql = "SELECT username, payment_status FROM {$wpdb->prefix}binarymlm_users WHERE sponsor_key = '".$pkey."' ORDER BY id DESC LIMIT 0,5";
	$results = $wpdb->get_results($sql);
  $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$users[$i]['username'] = $data->username;
			$users[$i]['payment_status'] = $this->letscms_activeNotActive($data->payment_status);
			$i++;
		}
	}
	else
	{
		$users[$i]['username'] = 'No Member Found';
		$users[$i]['payment_status'] = '';
	}
	return $users;
}

function letscms_get_post_id_or_postname_for_payout($page, $id)
{
	global $wpdb;
	$mlm_settings = get_option('permalink_structure');
    $sql = "SELECT id FROM {$wpdb->prefix}posts WHERE post_title = '".$page."'";
    $post_id = $wpdb->get_var($sql);
	if($mlm_settings == '/%postname%/')
	{
			$query="SELECT post_name FROM {$wpdb->prefix}posts WHERE id = $post_id";
			$post_name = $wpdb->get_var($query);
			return bloginfo('url')."/".$post_name."/?pid=$id";
	}
	return bloginfo('url')."/?page_id={$post_id}&pid=$id";
}

function letscms_getSponsorName($key)
{
	global $wpdb;
	$sql = "SELECT username	FROM {$wpdb->prefix}binarymlm_users WHERE user_key = '".$key."'";
	$username = $wpdb->get_var($sql);
	return $username;
}

function letscms_legPlacement($leg)
{
	if($leg == 0)
		return 'Left';
	else
		return 'Right';
}

function letscms_activeNotActive($status)
{
	if($status == '1')
		return 'Active';
	else
		return 'Not Active';
}

/************** Here begin the code for calculate and distribute the commission***********************/
function letscms_mlmIsEligibleForCommission($key)
{
	global $wpdb;
	//get the eligibility for commission and bonus
	$mlm_eligibility = get_option('letscms_mlm_eligibility_settings');
	$leftusers = 0;
	$rightusers =0;

	$sql="SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE banned = '0' AND  payment_status = '1' AND sponsor_key = '".$key."'";

	$results = $wpdb->get_results($sql);
	$num = $wpdb->num_rows;
	if($num)
	{
		foreach($results as $data)
		{
			$lactive = $wpdb->get_var("SELECT COUNT(*) AS lactive FROM {$wpdb->prefix}binarymlm_leftleg WHERE ukey = '".$data->user_key."' AND pkey = '".$key."'");

			if($lactive >= 1) { $leftusers++; }

			$ractive = $wpdb->get_var("SELECT COUNT(*) AS ractive FROM {$wpdb->prefix}binarymlm_rightleg WHERE ukey = '".$data->user_key."' AND pkey = '".$key."'");

			if($ractive >= 1) { $rightusers++; }
		} //end foreach loop

		//total direct referral including left and right
		$total_referral = $leftusers + $rightusers;
	} else {
        $leftusers = $wpdb->get_var("SELECT COUNT(*) AS lactive FROM {$wpdb->prefix}binarymlm_leftleg 
						WHERE pkey = '$key' AND commission_status='0'");
        $rightusers = $wpdb->get_var("SELECT COUNT(*) AS ractive FROM {$wpdb->prefix}binarymlm_rightleg 
						WHERE  pkey = '$key' AND commission_status='0'");
        $total_referral = 0;
    }

    if ($leftusers >= $mlm_eligibility['binarymlm_left_referral'] &&
        $rightusers >= $mlm_eligibility['binarymlm_right_referral'] &&
        $total_referral >= $mlm_eligibility['binarymlm_direct_referral']) {
        return true;
    } else{
    	return false;
  }
}

function letscms_mlmCalculateCommission($pkey)
{
	global $wpdb;
	//get the eligibility for commission
	$mlm_payout = get_option('letscms_mlm_payout_settings');

	$pair1 = $mlm_payout['binarymlm_pair1'];
	$pair2 = $mlm_payout['binarymlm_pair2'];
	
	//check users from left leg table
	$leftsql="SELECT  L.ukey FROM {$wpdb->prefix}binarymlm_leftleg as L join {$wpdb->prefix}binarymlm_users as U on U.user_key=L.ukey Where L.pkey = '".$pkey."' AND L.commission_status = '0' AND U.payment_status = '1' ORDER BY U.id LIMIT $pair1";
	
	$leftquery = $wpdb->get_results($leftsql);
	
	$leftno = $wpdb->num_rows;
		if($leftno >= $pair1)
  			{
			//check users from right leg table
			$rightsql="SELECT  R.ukey FROM {$wpdb->prefix}binarymlm_rightleg as R join {$wpdb->prefix}binarymlm_users as U on U.user_key=R.ukey Where R.pkey = '".$pkey."' AND R.commission_status = '0' AND U.payment_status = '1' AND U.special='0' ORDER BY U.id LIMIT $pair2";

			$rightquery = $wpdb->get_results($rightsql);
			$rgtno = $wpdb->num_rows;

				if($rgtno >= $pair2)
					{
						//mark users as paid and update commission table with child ids
            			$this->insertCommission($leftquery, $rightquery, $pkey);
            		}
           	}

    //check users from right leg table
	$rightsql="SELECT  R.ukey FROM {$wpdb->prefix}binarymlm_rightleg as R join {$wpdb->prefix}binarymlm_users as U on U.user_key=R.ukey Where R.pkey = '".$pkey."' AND R.commission_status = '0' AND U.payment_status = '1' AND U.special='0' ORDER BY U.id LIMIT $pair1";
	
	$rightquery = $wpdb->get_results($rightsql);
	
	$rgtno = $wpdb->num_rows;
		if($rgtno >= $pair1)
			{
			//check users from left leg table
			$leftsql="SELECT  L.ukey FROM {$wpdb->prefix}binarymlm_leftleg as L join {$wpdb->prefix}binarymlm_users as U on U.user_key=L.ukey Where L.pkey = '".$pkey."' AND L.commission_status = '0' AND U.payment_status = '1' ORDER BY U.id LIMIT $pair2";
			
			$leftquery = $wpdb->get_results($leftsql);
			$leftno = $wpdb->num_rows;

				if($leftno >= $pair2)
					{
						//mark users as paid and update commission table with child ids
            			$this->insertCommission($leftquery, $rightquery, $pkey);
        			}
    		}
}

function insertCommission($leftquery, $rightquery, $pkey) {
    global $wpdb;
    $mlm_payout = get_option('letscms_mlm_payout_settings');
    $initial_pair = $mlm_payout['binarymlm_initial_pair'];
    $initial_amount = $mlm_payout['binarymlm_initial_amount'];
    $further_amount = $mlm_payout['binarymlm_further_amount'];
    $childs = '';
    foreach ($rightquery as $rightresult) {
        $wpdb->query(" UPDATE {$wpdb->prefix}binarymlm_rightleg  SET commission_status = '1'  WHERE pkey = '$pkey'  AND ukey = '" . $rightresult->ukey . "'  LIMIT 1 ");
        $childs .= $this->letscms_mlmGetUserNameByKey($rightresult->ukey) . ",";
    }
    //mark users as paid and update commission table with child ids
    foreach ($leftquery as $leftresult) {
        $wpdb->query(" UPDATE {$wpdb->prefix}binarymlm_leftleg  SET commission_status = '1'  WHERE pkey = '$pkey'  AND ukey = '" . $leftresult->ukey . "'  LIMIT 1 ");
        $childs .= $this->letscms_mlmGetUserNameByKey($leftresult->ukey) . ",";
    }
    //give commission and mark users as paid
    $date = current_time('mysql');
    $parent_id = $this->letscms_getuseridbykey($pkey);
    $num = $wpdb->get_var("  SELECT COUNT(*) AS num  FROM {$wpdb->prefix}binarymlm_commission  WHERE parent_id = $parent_id ");

    $child_ids = trim($childs, ',');
    $users = explode(',', $childs);
    $comm_amt = '';
    foreach ($users as $user_name) {
    	$user_id=$this->letscms_getUserIdByUsername($user_name);
        $comm_amt += $this->letscms_getProducPrice($user_id);
    }

    if ($mlm_payout['binarymlm_init_pair_comm_type'] == 'Percentage') {
        $initial_amount = $comm_amt * $initial_amount / 100;
    }
    if ($mlm_payout['binarymlm_furt_amou_comm_type'] == 'Percentage') {
        $further_amount = $comm_amt * $further_amount / 100;
    }


    $amount = ($num >= $initial_pair) ? $further_amount : $initial_amount;
    $sql = " INSERT INTO {$wpdb->prefix}binarymlm_commission  ( id, date_notified, parent_id, child_ids, amount )  VALUES  ( NULL, '$date', '$parent_id', '" . $child_ids . "', '$amount' )  ";
    $wpdb->query($sql);
}

function letscms_getProducPrice($user_id) {
    global $wpdb;
    $var = $wpdb->get_var("SELECT product_price FROM {$wpdb->prefix}binarymlm_users WHERE user_id='$user_id'");
    return $var;
}

function letscms_getUserIdByUsername($username) {
    global $wpdb;
    $id = $wpdb->get_var(" SELECT ID  FROM {$wpdb->prefix}users  WHERE user_login = '$username' ");
    return $id;
}

function letscms_mlmGetUserNameByKey($key)
{
	global $wpdb;
	$username = $wpdb->get_var("SELECT username FROM {$wpdb->prefix}binarymlm_users WHERE user_key = '".$key."'");
	return $username;
}

function letscms_getuseridbykey($key)
{
	global $wpdb;
	$id = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}binarymlm_users WHERE `user_key` = '".$key."'");
	return $id;
}

function letscms_getuseruidbykey($key) 
{
    global $wpdb;
    $id = $wpdb->get_var("SELECT user_id FROM {$wpdb->prefix}binarymlm_users WHERE `user_key` = '$key'");
    return $id;
}

function letscms_mlmCalculateBonus($key)
{
	global $wpdb;
	//get the eligibility for bonus
	$mlm_bonus = get_option('letscms_mlm_bonus_settings');

	if($mlm_bonus['binarymlm_bonus_criteria'] == 'personal')
	{
		//count total direct referrals
		$query = $wpdb->get_var("SELECT COUNT(*) AS num	FROM {$wpdb->prefix}binarymlm_users	WHERE sponsor_key = '".$key."'	AND payment_status = '1' AND banned = '0'");
		$bonus_slab = $query;
	}
	else if($mlm_bonus['binarymlm_bonus_criteria'] == 'pair')
	{
		//count total active users on left leg
		$leftsql="SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_leftleg AS L JOIN {$wpdb->prefix}binarymlm_users AS U on U.user_key = L.ukey WHERE L.pkey = '".$key."' AND U.payment_status = '1' AND U.banned = '0'";
		$leftcount = $wpdb->get_var($leftsql);

		//count total active users on right leg
		$rightsql="SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_rightleg AS R JOIN {$wpdb->prefix}binarymlm_users AS U on U.user_key = R.ukey WHERE R.pkey = '".$key."' AND U.payment_status = '1' AND U.banned = '0'";
		$rightcount = $wpdb->get_var($rightsql);
		//count total numbers of active pair
		$paircase1 = $this->letscms_getPair($leftcount, $rightcount);
		$paircase2 = $this->letscms_getPair($rightcount, $leftcount);

		if($paircase1['pair'] >= $paircase2['pair'])
			$bonus_slab = $paircase1['pair'];
		else
			$bonus_slab = $paircase2['pair'];
	}

	$slabpair = $mlm_bonus['binarymlm_pair'];
	$slabamount = $mlm_bonus['binarymlm_amount'];

	//count total slabs defined for bouns
	$totalslabs = count($slabpair);

	//get mlm_user_id
	$mlm_user_id = $this->letscms_getuseridbykey($key);

	$flag = 1;
	while($flag)
	{
		$num = $this->letscms_distributeBonusSlab($mlm_user_id);

		if (!empty($bonus_slab) && $bonus_slab >= $slabpair[$num] && ($num < $totalslabs)) 
		{
			$this->letscms_insertBonusSlab($mlm_user_id, $slabamount[$num]);
		}
		else
			$flag = 0;
	}
}

function letscms_getPair($leftcount, $rightcount)
{
	$mlm_payout = get_option('letscms_mlm_payout_settings');

	$pair1 = $mlm_payout['binarymlm_pair1'];
	$pair2 = $mlm_payout['binarymlm_pair2'];

	$leftpair = (int)($leftcount/$pair1);
	$rightpair = (int)($rightcount/$pair2);

	if($leftpair <= $rightpair)
		$pair = $leftpair;
	else
		$pair = $rightpair;

	$leftbalance = $leftcount - ($pair * $pair1);
	$rightbalance = $rightcount - ($pair * $pair2);

	$array['leftbal'] = $leftbalance;
	$array['rightbal'] = $rightbalance;
	$array['pair'] = $pair;

	return $array;
}

function letscms_distributeBonusSlab($mlm_user_id)
{
	global $wpdb;
	//count how many times bonus have been paid by the system previously
	$cb = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_bonus	WHERE mlm_user_id = '".$mlm_user_id."'");
	return $cb;
}

function letscms_insertBonusSlab($mlm_user_id, $amount)
{
	global $wpdb;
	$date = date('Y-m-d H:i:s');
	//deduct service charge and tds
	$insert = $wpdb->query("INSERT INTO {$wpdb->prefix}binarymlm_bonus(id, date_notified, mlm_user_id, amount)	VALUES(NULL, '".$date."', '".$mlm_user_id."', '".$amount."')");
}


function letscms_payoutRun()
{
	$returnArr['displayData'] = $this->wpmlm_run_pay_display_functions();
	$returnArr['directRun'] = '';
	$returnArr['msgforpro'] = '';

	return $returnArr;
}

function letscms_getBonusAmountById($userId)
{
	global $wpdb;
	$sql = "SELECT amount, SUM(amount) AS bonus, payout_id	FROM	{$wpdb->prefix}binarymlm_bonus	WHERE	mlm_user_id ='".$userId."' and payout_id = 0	GROUP BY	mlm_user_id";
	$rs=$wpdb->get_results($sql);
	if($wpdb->num_rows>0)
	{
		foreach($rs as $row){
			$bonus = $row->bonus;
		}
	}
	return $bonus;
}

function letscms_getCommissionById($userId) {
    global $table_prefix, $wpdb;
    $sql = "SELECT SUM(amount) AS commission FROM {$wpdb->prefix}binarymlm_commission WHERE  payout_id=0 AND parent_id=$userId GROUP BY parent_id";
    $commission = $wpdb->get_var($sql);

    return $commission;
}

function letscms_getReferralCommissionById($userId) {
    global $table_prefix, $wpdb;
    $sql = "SELECT SUM(amount) AS reff_comm FROM {$wpdb->prefix}binarymlm_referral_commission WHERE   sponsor_id ='$userId' AND   payout_id=0 GROUP BY sponsor_id";
    $reff_comm = $wpdb->get_var($sql);

    return $reff_comm;
}

function letscms_getUserInfoByMlmUserId($mlmUserId)
{
		global $wpdb;
		$user_id = $wpdb->get_var("SELECT user_id FROM {$wpdb->prefix}binarymlm_users WHERE id = $mlmUserId");
		$user_info = get_userdata($user_id);
		return $user_info;
}

/* * ******** Random ePin Genarate ************* */

function epin_genarate($no) {

    /// Random characters
    $characters = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

    // set the array
    $keys = array();

    // set length
    $length = $no;

    // loop to generate random keys and assign to an array
    while (count($keys) < $length) {
        $x = mt_rand(0, count($characters) - 1);
        if (!in_array($x, $keys)) {
            $keys[] = $x;
        }
    }

    // extract each key from array
    $random_chars = '';
    foreach ($keys as $key) {
        $random_chars .= $characters[$key];
    }

    // display random key
    return $random_chars;
}

function letscms_checkepin(){
	global $wpdb;

	$q = $_REQUEST['q'];
	$epin = $wpdb->get_var("SELECT epin_no FROM {$wpdb->prefix}binarymlm_epins WHERE epin_no = '$q' AND status=0");
	if ($epin) {
	    _e("<span class='msg'>Congratulations! This ePin is available.</span>", "mlm");
	}
	else {
	    _e("<span class='errormsg'>Sorry! This ePin is not Valid or already Used .</span>", "mlm");
	}
}

function mlmUserUpdateePin($user_id, $epin) {
    global $wpdb;
    $mlm_general_settings = get_option('letscms_mlm_general_settings');
	$p_id = $wpdb->get_var("select p_id from {$wpdb->prefix}binarymlm_epins where epin_no = '{$epin}'");
	$pc = $wpdb->get_var("SELECT product_price FROM {$wpdb->prefix}binarymlm_product_price WHERE p_id = '" . $p_id . "'");
    $pointStatus = $wpdb->get_row("select point_status from {$wpdb->prefix}binarymlm_epins where epin_no = '{$epin}'", ARRAY_N);
    // to epin point status 1 
    if ($pointStatus[0] == '1') {
        $paymentStatus = '1';
        $product_price = $pc;
    }
    // to epin point status 1 
    else if ($pointStatus[0] == '0') {
        $paymentStatus = '2';
        $product_price = '0';
    }
    else {
        $paymentStatus = '0';
        $product_price = '0';
    }
    $sql = "UPDATE {$wpdb->prefix}binarymlm_users SET payment_status='{$paymentStatus}', product_price='{$product_price}',payment_date='".current_time('mysql')."' WHERE user_id='{$user_id}'";
    if ($wpdb->query($sql)) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function letscms_sponsor_name_availabilty(){
	global $wpdb;
	$q = $_REQUEST['q'];
    $sname = $wpdb->get_var("SELECT username FROM {$wpdb->prefix}binarymlm_users WHERE `username` = '$q'");

    if (!$sname)
        _e("<span class='errormsg'>Sorry! The specified sponsor is not valid </span>", "mlm");
    else
        _e("<span class='msg'>Congratulations! Your sponsor is <b> " . ucwords(strtolower($sname)) . "</b> .</span>");
}

function insert_refferal_commision($user_id) {
    global $wpdb;
    $date = current_time('mysql');
    $mlm_payout = get_option('letscms_mlm_payout_settings');
    $refferal_amount = $mlm_payout['binarymlm_referral_commission_amount'];
    if ($mlm_payout['binarymlm_dir_ref_comm_type'] == 'Percentage') {
        $refferal_amount = $this->letscms_getProducPrice($user_id) * $refferal_amount / 100;
    }

    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}binarymlm_users WHERE user_id=$user_id");
    $sponsor_key = $row->sponsor_key;
    $child_id = $row->id;
    if ($sponsor_key != 0) {
        $sponsor = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}binarymlm_users WHERE user_key='" . $sponsor_key . "'");
        $sponsor_id = $sponsor->id;
        $sql = "INSERT INTO {$wpdb->prefix}binarymlm_referral_commission SET date_notified ='$date',sponsor_id='$sponsor_id',child_id='$child_id',amount='$refferal_amount',payout_id='0' ON DUPLICATE KEY UPDATE child_id='$child_id'";
        $rs = $wpdb->query($sql);
    }
}

function letscms_update_epin(){
		global $wpdb;
		$user_id = $_REQUEST['userId'];
		$epin = $_REQUEST['epin'];
		$user_key = $this->letscms_getuserkeybyid($user_id);
		if (!empty($epin)) {
	    $sql = "SELECT * FROM {$wpdb->prefix}binarymlm_epins WHERE epin_no='" . $epin . "' AND status=1";
	    $results = $wpdb->get_results($sql);

	    if (empty($wpdb->num_rows) &&  $wpdb->num_rows != 1) {
	        $sql = "update {$wpdb->prefix}binarymlm_epins set user_key='{$user_key}', date_used='".current_time('mysql')."', status=1 where epin_no ='{$epin}' ";
	        $epinUpdate = $wpdb->query($sql);
			if (!empty($epinUpdate)) {
	            $userUpdate = $this->mlmUserUpdateePin($user_id, $epin);
	            $status = $wpdb->get_var("SELECT payment_status FROM {$wpdb->prefix}binarymlm_users WHERE `user_id` = '$user_id'");
	            if ($status == '1') {
	                $this->insert_refferal_commision($user_id);
	            }
				if ($userUpdate) {
					_e("<span class='error' style='color:green'>Congratulations! Your account is now set to Active.</span>");
				}
	        }
	    } else {
	        _e("<span class='error' style='color:red'>Sorry. You have entered an invalid ePin.</span>");
	    }
	}
}

// To perform the changes in value of custom column
		function payment_status_change_ajax()
		{
			global $wpdb;
			$date = date("Y-m-d H:i:s");
				if(isset($_REQUEST['userId']) && isset($_REQUEST['status']))
			{
					$sql = "UPDATE {$wpdb->prefix}binarymlm_users SET payment_status = '".$_REQUEST['status']."', payment_date='$date' WHERE user_id = '".$_REQUEST['userId']."'";
					$rs = $wpdb->query($sql);

					if(!$rs){
						return "<span class='error' style='color:red'>Updating Fail</span>";
					} else{
						return "<span class='error' style='color:green'>success</span>";
					}
			 }

		}




function PayoutGeneratedMail($mlmuserId, $amount, $payoutMasterId) {
    global $wpdb;
    $res = $wpdb->get_var("SELECT user_id FROM {$wpdb->prefix}binarymlm_users WHERE `id` = '" . $mlmuserId . "' ");
    $user_info = get_userdata($res);
    $siteownwer = get_bloginfo('name');

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $headers .= "From: " . get_option('admin_email') . "<" . get_option('admin_email') . ">" . "\r\n";

    $subject = get_option('letscms_mlm_runpayout_email_subject', true);
    $message = nl2br(htmlspecialchars(get_option('letscms_mlm_runpayout_email_message', true)));
    $message = str_replace('[firstname]', $user_info->first_name, $message);
    $message = str_replace('[lastname]', $user_info->last_name, $message);
    $message = str_replace('[email]', $user_info->user_email, $message);
    $message = str_replace('[username]', $user_info->user_login, $message);
    $message = str_replace('[amount]', $amount, $message);
    $message = str_replace('[payoutid]', $payoutMasterId, $message);
    $message = str_replace('[sitename]', $siteownwer, $message);
    wp_mail(get_option('admin_email'), $subject, $message, $headers);
    wp_mail($user_info->user_email, $subject, $message, $headers);
}

// If apply for with drawal From Front End
 
function WithDrawalInitiatedMail($mlmId, $comment, $payoutId) {
    global $wpdb;
    $res = $wpdb->get_var("SELECT user_id FROM {$wpdb->prefix}binarymlm_users WHERE `id` = '" . $mlmId. "'");
    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}binarymlm_payout WHERE `payout_id` = '$payoutId' AND user_id='$mlmId'");
    $user_info = get_userdata($res); 
    $siteownwer = get_bloginfo('name');
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $headers .= "From: " . get_option('admin_email') . "<" . get_option('admin_email') . ">" . "\r\n";
    $subject = get_option('letscms_mlm_withdrawalintiate_email_subject', true);
    $message = nl2br(htmlspecialchars(get_option('letscms_mlm_withdrawalintiate_email_message', true)));
    $message = str_replace('[firstname]', $user_info->first_name, $message);
    $message = str_replace('[lastname]', $user_info->last_name, $message);
    $message = str_replace('[email]', $user_info->user_email, $message);
    $message = str_replace('[username]', $user_info->user_login, $message);
    $message = str_replace('[amount]', $row->capped_amt, $message);
    $message = str_replace('[mode]', $row->payment_mode, $message);
    $message = str_replace('[comment]', $comment, $message);
    $message = str_replace('[payoutid]', $payoutId, $message);
    $message = str_replace('[sitename]', $siteownwer, $message); 
    wp_mail(get_option('admin_email'), $subject, $message, $headers);
    wp_mail($user_info->user_email, $subject, $message, $headers);
}

function WithDrawalProcessedMail($userId, $mode, $amount, $payoutId) {
    global $wpdb;
    $res = $wpdb->get_var("SELECT user_id FROM {$wpdb->prefix}binarymlm_users WHERE `id` = '" . $userId . "' ");
    $user_info = get_userdata($res);
    $siteownwer = get_bloginfo('name');

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $headers .= "From: " . get_option('admin_email') . "<" . get_option('admin_email') . ">" . "\r\n";

    $subject = get_option('letscms_mlm_withdrawalProcess_email_subject', true);

    $message = nl2br(htmlspecialchars(get_option('letscms_mlm_withdrawalProcess_email_message', true)));
    $message = str_replace('[firstname]', $user_info->first_name, $message);
    $message = str_replace('[lastname]', $user_info->last_name, $message);
    $message = str_replace('[email]', $user_info->user_email, $message);
    $message = str_replace('[username]', $user_info->user_login, $message);
    $message = str_replace('[amount]', $amount, $message);
    $message = str_replace('[withdrawalmode]', $mode, $message);
    $message = str_replace('[payoutid]', $payoutId, $message);
    $message = str_replace('[sitename]', $siteownwer, $message);

    wp_mail(get_option('admin_email'), $subject, $message, $headers);
    wp_mail($user_info->user_email, $subject, $message, $headers);
}

function calculatelegUsersByPayoutId($user_key, $payout_id) {
    for ($x = $payout_id; $x > 0; $x--) {
        $pid[] = $x;
    }
    $payout_id = implode("','", $pid);
    $left_users = $this->totalLeftLegUsersByPayoutId($user_key, $payout_id);
    $right_users = $this->totalRightLegUsersByPayoutId($user_key, $payout_id);
    if ($left_users < $right_users) {
        $pairs = $left_users;
    }
    else {
        $pairs = $right_users;
    }
    return array('l' => $left_users, 'r' => $right_users, 'p' => $pairs);
}

function totalLeftLegUsersByPayoutId($pkey, $payout_id) {
    global $wpdb;
    $num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_leftleg WHERE pkey = '$pkey' and payout_id in('$payout_id')");
    
     return $num; 
}

function totalRightLegUsersByPayoutId($pkey, $payout_id) {
    global $wpdb;
    $num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_rightleg WHERE pkey = '$pkey' and payout_id in('$payout_id')
    ");
    return $num;
}

function letscms_mlm_reset_data(){
	global $wpdb;
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && is_super_admin()) {
		
		/*******************To Delete Options*************************/
		$sql = "select * from $wpdb->options WHERE option_name LIKE '%mlm%'";
		$res = $wpdb->get_results($sql);
		foreach($res as $row){
			delete_option($row->option_name);
		}
		
		/*******************To delete mlm users***********************/
    	$user_ids = $wpdb->get_results("select user_id from {$wpdb->prefix}binarymlm_users");
		foreach ($user_ids as $row) {
			wp_delete_user($row->user_id , $reassign = null);
		}

		/*******************To truncate tables*************************/
		$tables = array(
			"{$wpdb->prefix}binarymlm_users",
			"{$wpdb->prefix}binarymlm_leftleg",
			"{$wpdb->prefix}binarymlm_rightleg",
			"{$wpdb->prefix}binarymlm_commission",
			"{$wpdb->prefix}binarymlm_bonus",
			"{$wpdb->prefix}binarymlm_payout_master",
			"{$wpdb->prefix}binarymlm_payout",
			"{$wpdb->prefix}binarymlm_epins",
			"{$wpdb->prefix}binarymlm_product_price",
			"{$wpdb->prefix}binarymlm_referral_commission",
		);
		foreach( $tables as $table ) {
			$wpdb->query( "TRUNCATE TABLE $table" ); 
		}
		if ($wpdb->get_var("select count(p_id)as count from {$wpdb->prefix}binarymlm_product_price") < 1) {
        $wpdb->query("INSERT INTO {$wpdb->prefix}binarymlm_product_price set product_name='Free ePin',product_price=0");
    	}

		/*********************To unset session variabla**************************/
		unset($_SESSION['search_user']);
?>
	<div class='notibar msgsuccess'>
		<p>All MLM data has been successfully deleted from the system.</p>
		<p>You can start it again by creating the First User of the network by <strong><a href="<?php admin_url() ?>admin.php?page=binarymlm-admin-settings&tab=">Clicking here.</a></strong></p>
	</div>

<?php
 	} else{ 
 		echo "<div class='notibar msgerror'>
          <p> <strong>You are not authenticated to reset data.</strong></p>
      		  </div>";
	}
}

function has_buy() {
    $binarymlm_user_email = get_option('binarymlm_user_email');
    $params = 'binarymlm_user_email=' . $binarymlm_user_email;
    $ch = curl_init(WP_BINARY_MLM_ULR . '/Auth/key.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return !empty($result) ? 1 : 0;
}

}
