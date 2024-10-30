<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Network_Details {
use letscms_mlmfunction;
public function letscms_network_details(){
global $current_user,$wpdb;
$this->letscms_check_user();
      //get current user name
      $user_name = $current_user->user_login;
      $sponsor_name = $current_user->user_login;
      $user_info = get_userdata($current_user->ID);

    	//get loged user's key
    	$key = $this->letscms_get_current_user_key();

    	//Total Users on My left leg
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

      $add_page_id = $this->letscms_get_post_id(LETSCMS_MLM_REGISTRATION_TITLE);
    	$_SESSION['ajax'] = 'ajax_check';

    	$affiliateURL = site_url().'?page_id='.$add_page_id.'&sp='.$key;
    	$affiliateURL1 = site_url().'/letscms/'.$user_name;
      ?>
      <p class="affiliate_url"><strong>Affiliate URL :</strong> <?= $affiliateURL1 ?> </p><br />

      <div class="row-fluid">
      <div class="span4 sc-col">
      <div class="tabbable  "><ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab1">Left Leg Sales</a></li></ul>
        <div class="tab-content">
      <div id="tab1" class="tab-pane active ">
      <table class="table" width="100%" cellspacing="10" cellpadding="1" border="0">
      <tbody>
      <tr>
      <td><span style="color: #000000;">Total on Left Leg: <?=$leftLegUsers ?></span></td>
      <td><span style="color: #000000;">Active: <?= $leftLegActiveUsers?></span></td>
      </tr>
      <?php
      		foreach($fiveLeftLegUsers as $key => $value)
      		{
      			echo "<tr>";
      			foreach($value as $k=>$val){ echo "<td>".$val."</td>"; }
      			echo "</tr>";
      		} 
      		?>
      <tr>
      <td colspan="2"><span style="color: #800000;"><a href="<?= $this->letscms_get_post_id_or_postname(LETSCMS_MLM_LEFT_GROUP_DETAILS_TITLE);?>" style="text-decoration: none;"><span style="color: #800000;">View All</span></a></span></td>
      </tr>
      </tbody>
      </table></div></div></div></div>

      <div class="span4 sc-col">
      <div class="tabbable  "><ul class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#tab2">Right Leg Sales</a></li></ul><div class="tab-content">
      <div id="tab1" class="tab-pane active ">
      <table class="table" width="100%" cellspacing="10" cellpadding="1" border="0">
      <tbody>
      <tr>
      <td><span style="color: #000000;">Total on Right Leg: <?= $rightLegUsers?></span></td>
      <td><span style="color: #000000;">Active: <?= $rightLegActiveUsers?></span></td>
      </tr>
      <?php
      		foreach($fiveRightLegUsers as $key => $value)
      		{
      			echo "<tr>";
      			foreach($value as $k=>$val)	{ echo "<td>".$val."</td>"; }
      			echo "</tr>";
      		}
      		?>
      <tr>
      <td colspan="2"><span style="color: #800000;"><a href="<?= $this->letscms_get_post_id_or_postname(LETSCMS_MLM_RIGHT_GROUP_DETAILS_TITLE);?>" style="text-decoration: none;"><span style="color: #800000;">View All</span></a></span></td>
      </tr>
      </tbody>
      </table></div></div></div></div>

      <div class="span4 sc-col">
      <div class="tabbable  "><ul class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#tab3">Personal Sales</a></li></ul><div class="tab-content">
      <div id="tab1" class="tab-pane active ">
      <table class="table" width="100%" cellspacing="10" cellpadding="1" border="0">
      <tbody>
      <tr>
      <td><span style="color: #000000;">My Personal Sales: <?= $personalSales?></span></td>
      <td><span style="color: #000000;">Active: <?= $activePersonalSales?></span></td>
      </tr>
      <?php
      		foreach($fivePersonalUsers as $key => $value)
      		{
      			echo "<tr>";
      			foreach($value as $k=>$val){ echo "<td>".$val."</td>"; }
      			echo "</tr>";
      		}
      		?>
      <tr>
      <td colspan="2"><span style="color: #800000;"><a href="<?= $this->letscms_get_post_id_or_postname(LETSCMS_MLM_PERSONAL_GROUP_DETAILS_TITLE);?>" style="text-decoration: none;"><span style="color: #800000;">View All</span></a></span></td>
      </tr>
      </tbody>
      </table></div></div></div></div>
      </div>

      <p style="text-align: center;"><a style="background:#051130" class="btn btn-large " href="<?= $this->letscms_get_post_id_or_postname(LETSCMS_MLM_GENEALOGY_TITLE);?>"><i class="none "></i><span style="color:#ffffff">View Genealogy</span></a></p>

      <div class="row-fluid"><div class="span4 sc-col">
      <div class="tabbable  "><ul class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#tab4">Personal Details</a></li></ul><div class="tab-content">
      <div id="tab1" class="tab-pane active ">
      <table class="table" width="100%" cellspacing="10" cellpadding="1" border="0">
      <tbody>
      <tr><td scope="row"><span style="color: #000000;">Title</span></td><td>Details</td></tr>
      <tr><td scope="row"><span style="color: #000000;">Name</span></td><td><?=$user_info->first_name.' '.$user_info->last_name ?></td></tr>
      <tr><td scope="row"><span style="color: #000000;">Address</span></td><td style="white-space: normal;"><?=$user_info->user_address1."<br>".$user_info->user_address2 ?></td></tr>
      <tr><td scope="row"><span style="color: #000000;">City</span></td><td><?=$user_info->user_city ?></td></tr>
      <tr><td scope="row"><span style="color: #000000;">Contact No.</span></td><td><?=$user_info->user_telephone ?></td></tr>
      <tr><td scope="row"><span style="color: #000000;">DOB</span></td><td><?=$user_info->user_dob ?></td></tr>
      <tr><td><span style="color: #800000;"><a href="<?= $this->letscms_get_post_id_or_postname(LETSCMS_MLM_UPDATE_PROFILE_TITLE);?>" style="text-decoration: none;"><span style="color: #800000;">Edit</span></a></span></td>
      <td></td></tr>
      </tbody>
      </table></div></div></div></div>

      <div class="span4 sc-col">
      <div class="tabbable "><ul class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#tab5">My Payouts</a></li></ul><div class="tab-content">
      <div id="tab1" class="tab-pane active ">
      <table class="table" width="100%" cellspacing="10" cellpadding="1" border="0">
      <tbody>
      <tr><td scope="row"><span style="color: #000000;">Date</span></td>
          <td><span style="color: #000000;">Total Amount</span></td>
          <td><span style="color: #000000;">Deduction</span></td>
          <td><span style="color: #000000;">Amount</span></td>
          <td><span style="color: #000000;">Action</span></td>
      </tr>
      <?php
      $MLM_My_Payout = new Letscms_MLM_My_Payout();
      $detailsArr =  $MLM_My_Payout->letscms_my_payout_function();
      if(count($detailsArr)>0){
      $mlm_settings = get_option('letscms_mlm_general_settings');
      foreach($detailsArr as $row) {
      $totalAmount=$row->commission_amount + $row->bonus_amount + $row->referral_amount;
      $deduction=$row->tax + $row->service_charge;
      $amount=$totalAmount - $deduction;
      ?>
		  <tr>
			<td><?= $row->payoutDate ?></td>
      <td><?php echo $mlm_settings['binarymlm_currency'].' '. $totalAmount;?></td>
      <td><?php echo $mlm_settings['binarymlm_currency'].' '.$deduction;?></td>
      <td><?php echo $mlm_settings['binarymlm_currency'].' '.$amount ?></td>
			<td><a href="<?= $this->letscms_get_post_id_or_postname_for_payout(LETSCMS_MLM_MY_PAYOUT_DETAILS, $row->payout_id)?>">View</a></td>
		  </tr>
      <?php
    } } ?>
</tbody>
</table></div></div></div></div>

</div>
<?php
  }// end of function letscms_network_details()
}// end of class
