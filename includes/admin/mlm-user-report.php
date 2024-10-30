<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_User_Report{
use letscms_mlmfunction;

public function adminMLMUserAccount(){
$this->letscms_check_first_user();
global $wpdb;
$msg = '';

if(isset($_POST['mlm_user_account']))
				{
					$search = $_POST['search_user'];
					$userId = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}users WHERE user_login = '$search' OR user_email = '$search'");
      				if($wpdb->num_rows > 0)
					{
							$_SESSION['search_user'] = $search;
							$_SESSION['session_set'] = 'sets';
							$_SESSION['userID'] = $userId;
					}	else {
							$msg = 'You have entered a wrong username or email address';
							$_SESSION['search_user'] = $search;
							$_SESSION['session_set'] = '';
							$_SESSION['userID'] = '';
					}
        		}
?>
<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1>Binary MLM User Report</h1>

<div class="container1" >
<div class="box1" ><h4 align="center">User Report</h4></div>
<div class="wrap2">
	
<form name="open_user_account" method="post" action="">
	<div class="col-md-12">
		<div class="pull-right">
		<a href="?page=binarymlm-user-account" style="cursor:pointer;text-decoration:none; margin-left:90px;"><?php _e('Back to User Dashboard')?></a>
		</div>
		
		<div class="form-group">
			<label><a style="cursor:pointer; color:#2d5c8a;" title="Click for Help!" onclick="toggleVisibility('search-user');"><?php _e('Search By username or email address <span style="color:red;">*</span>:');?> </a></label>
			<input class="form-control" type="text" name="search_user" id="search_user" size="52" value="<?= isset($_SESSION['search_user'])?htmlentities($_SESSION['search_user']):'';?>">
			<div class="toggle-visibility" id="search-user"><?php _e('Please enter username or email address.');?></div>
			<div style="color:red;"><?=$msg?></div>
			<p class="submit">
				<button type="submit" name="mlm_user_account" id="mlm_user_account" class="btn btn-primary btn-default" >Search &raquo;</button>
			</p>
		</div>
	</div>
</form></div>
<?php
if(!empty($_GET['ac']) && $_GET['ac'] == 'edit' && $_GET['page'] == 'binarymlm-user-account'){
		 $MLM_Update_Profile = new Letscms_MLM_Update_Profile();
	   $MLM_Update_Profile->letscms_update_profile($_SESSION['userID']); }

else if(!empty($_GET['ac']) && $_GET['ac'] == 'leftleg' && $_GET['page'] == 'binarymlm-user-account'){
			$MLM_My_Left_Group = new Letscms_MLM_My_Left_Group();
			$MLM_My_Left_Group->letscms_left_group($_SESSION['userID']); }

else if(!empty($_GET['ac']) && $_GET['ac'] == 'rightleg' && $_GET['page'] == 'binarymlm-user-account'){
			$MLM_My_Right_Group = new Letscms_MLM_My_Right_Group();
			$MLM_My_Right_Group->letscms_right_group($_SESSION['userID']); }

else if(!empty($_GET['ac']) && $_GET['ac'] == 'personal' && $_GET['page'] == 'binarymlm-user-account'){
			$MLM_My_Personal_Group = new Letscms_MLM_My_Personal_Group();
			$MLM_My_Personal_Group->letscms_personal_group($_SESSION['userID']); }

else if(!empty($_GET['ac']) && $_GET['ac'] == 'payout' && $_GET['page'] == 'binarymlm-user-account'){
			$MLM_My_Payout = new Letscms_MLM_My_Payout();
			$MLM_My_Payout->letscms_my_payout($_SESSION['userID']); }

else if(!empty($_GET['ac']) && $_GET['ac'] == 'payout-details' && $_GET['page'] == 'binarymlm-user-account'){
			$MLM_My_Payout_Details = new Letscms_MLM_My_Payout_Details();
			$MLM_My_Payout_Details->letscms_my_payout_details($_SESSION['userID']); }

else if(!empty($_GET['ac']) && $_GET['ac'] == 'network' && $_GET['page'] == 'binarymlm-user-account'){
			$MLM_Admin_Genealogy = new Letscms_MLM_Admin_Genealogy();
			$MLM_Admin_Genealogy->letscms_admin_genealogy_network($_SESSION['userID']); }

else if(!empty($_SESSION['session_set']) && $_SESSION['session_set'] == 'sets'){
$key = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE user_id = {$_SESSION['userID']}");

	//Total Users on my left leg
  	$leftLegUsers = $this->letscms_totalLeftLegUsers($key);

	//Total users on my right leg
	$rightLegUsers = $this->letscms_totalRightLegUsers($key);

	//paid users on my left leg
	$leftLegActiveUsers = $this->letscms_activeUsersOnLeftLeg($key);

	//paid users on my right leg
	$rightLegActiveUsers = $this->letscms_activeUsersOnRightLeg($key);

	//Total my personal sales
	$personalSales = $this->letscms_totalMyPersonalSales($key);

	//Total my personal sales active users
	$activePersonalSales = $this->letscms_activeUsersOnPersonalSales($key);

	//show five users on left leg
	$fiveLeftLegUsers = $this->letscms_myFiveLeftLegUsers($key);

	//show five users on right leg
	$fiveRightLegUsers = $this->letscms_myFiveRightLegUsers($key);

	//show five users on personal sales
	$fivePersonalUsers = $this->letscms_myFivePersonalUsers($key);

  $user_info = get_userdata($_SESSION['userID']);

  $add_page_id =$this-> letscms_get_post_id(LETSCMS_MLM_REGISTRATION_TITLE);
  $sponsor_name = $user_info->user_login;
  $affiliateURL = site_url().'?page_id='.$add_page_id.'&sponsor='.$sponsor_name;
}
if(!empty($user_info)){
?>
<div class="col-md-12">
	<table class="table" >
		  <tr>
			<td width="40%" valign="top">			
			<table class="table" >			
			  <thead><tr><td colspan="2" align="center" ><strong>Personal Information</strong></td></tr></thead>
			  <tr><td scope="row">Title</td><td>Details</td></tr>
			  <tr><td scope="row">Name</td><td><?php echo $user_info->first_name.' '.$user_info->last_name ?></td></tr>
			  <tr><td scope="row">Address</td><td style="white-space:normal;"><?=$user_info->user_address1."<br>".$user_info->user_address2 ?></td></tr>
			  <tr><td scope="row">City</td><td><?=$user_info->user_city ?></td></tr>
			  <tr><td scope="row">Contact No.</td><td><?=$user_info->user_telephone ?></td></tr>
			  <tr><td scope="row">DOB</td><td><?=$user_info->user_dob ?></td></tr>
				<tr><td><a href="?page=binarymlm-user-account&ac=edit" style="text-decoration: none">Edit</a></td>
						<td><a href="?page=binarymlm-user-account&ac=network" style="text-decoration: none">View Genealogy</a></td></tr>
			</table>

			<table class="table" >
				  <thead><tr><td colspan="3" align="center"><strong>My Payouts</strong></td></tr></thead>
				  <tr><td scope="row">Date</td><td>Amount</td><td>Action</td></tr>

<?php
$MLM_My_Payout = new Letscms_MLM_My_Payout();
$detailsArr =  $MLM_My_Payout->letscms_my_payout_function($_SESSION['userID']);

if(count($detailsArr)>0){
$mlm_settings = get_option('letscms_mlm_general_settings');

foreach($detailsArr as $row) :
					$amount = $row->commission_amount + $row->bonus_amount + $row->referral_commission_amount - $row->tax - $row->service_charge;
?>
					<tr><td><?= $row->payoutDate ?></td><td><?= $mlm_settings['binarymlm_currency'].' '.$amount ?></td>
							<td><a href="?page=binarymlm-user-account&ac=payout-details&pid=<?=$row->payout_id?>" style="text-decoration:none;">View</a></td></tr>
					<tr><td colspan="3"><a href="?page=binarymlm-user-account&ac=payout" style="text-decoration:none;">View All</a></td></tr>
<?php endforeach;
}else{
	?>
			<div class="no-payout"><span style="color:red;"> You have not earned any commisssions yet. </span></div>
<?php } ?>
				</table></td>

				<td width="40%">
				<table class="table" width="100%" border="0" cellspacing="10" cellpadding="1">
				<thead><tr><td align="center"><strong>Network Details</strong></td></tr></thead>
				<tr><td>
						<table width="100%" border="0" cellspacing="10" cellpadding="1">
						<tr><td colspan="2"><strong>Left Leg Sales</strong></td></tr>
						<tr><td>Total on Left Leg: <?=$leftLegUsers ?></td><td>Active: <?= $leftLegActiveUsers?></td></tr>

<?php
foreach($fiveLeftLegUsers as $key => $value)
{
	echo "<tr>";
	foreach($value as $k=>$val)
	{
	echo "<td>".$val."</td>";
	}
	echo "</tr>";
}	?>
						<tr><td colspan="2"><a href="?page=binarymlm-user-account&ac=leftleg" style="text-decoration: none">View All</a></td></tr>
						</table>
				</td></tr>
			  	<tr><td>
						<table width="100%" border="0" cellspacing="10" cellpadding="1">
						<tr><td colspan="2"><strong>Right Leg Sales</strong></td></tr>
						<tr><td>Total on Right Leg: <?= $rightLegUsers?></td><td>Active: <?= $rightLegActiveUsers?></td></tr>

<?php
foreach($fiveRightLegUsers as $key => $value)
{
	echo "<tr>";
	foreach($value as $k=>$val)
	{
	echo "<td>".$val."</td>";
	}
	echo "</tr>";
}
?>
						<tr><td colspan="2"><a href="?page=binarymlm-user-account&ac=rightleg" style="text-decoration: none">View All</a></td></tr>
							</table>
					</td></tr>

					<tr><td>
							<table width="100%" border="0" cellspacing="10" cellpadding="1">
							<tr><td colspan="2"><strong>Personal Sales</strong></td></tr>
							<tr><td>My Personal Sales: <?= $personalSales?></td><td>Active: <?= $activePersonalSales?></td></tr>
<?php
foreach($fivePersonalUsers as $key => $value)
{
		echo "<tr>";
		foreach($value as $k=>$val)
		{
		echo "<td>".$val."</td>";
		}
		echo "</tr>";
}
?>
<tr><td colspan="2"><a href="?page=binarymlm-user-account&ac=personal" style="text-decoration: none">View All</a></td></tr>
						 </table>
				</td></tr>
				</table>

			</td></tr>
</table>
<?php
}}
}
