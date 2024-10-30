<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Withdrawal_Request{
	 use letscms_mlmfunction;
	public function mlm_withdrawal_request() {
    	global $wpdb;
		$this->letscms_check_first_user();
	    include_once ABSPATH . '/wp-admin/includes/plugin.php';
	    if (is_plugin_active('mlm-paypal-mass-pay/load-data.php')) {
	        //plugin is activated
	        $this->mlm_withdrawal_request_MASS_Active();
	    }
	    else {
	        $this->mlm_withdrawal_request_MASS_Inactive();
	    }
	}

	public function mlm_withdrawal_request_MASS_Inactive() { 
	    global $wpdb;
	    global $date_format;
	    $url = plugins_url();
	    $ajax_url=admin_url( 'admin-ajax.php' );
	    ?>
	    <div class='wrap'>
	        <div id="icon-users" class="icon32"></div><h1>Withdrawal Request</h1>
	        
		<div class="container1" >
		<div class="box1" ><h4 align="center">Pending User Withdrawals</h4></div>
		<div class="wrap2">
		    <?php
		    $sql = "SELECT id, user_id, DATE_FORMAT(`date`,'%d %b %Y') as payoutDate, payout_id, commission_amount,referral_commission_amount, bonus_amount,total_amt,capped_amt,cap_limit, tax, service_charge, DATE_FORMAT(`withdrawal_initiated_date`,'%d %b %Y')withdrawal_initiated_date, withdrawal_initiated_comment FROM {$wpdb->prefix}binarymlm_payout WHERE withdrawal_initiated= 1 AND `payment_processed`= 0";

		    $rs = $wpdb->get_results($sql, ARRAY_A);

			    $listArr[-1]['name'] = __('Member Username', 'mlm');
			    $listArr[-1]['email'] = __('Member Email', 'mlm');
			    $listArr[-1]['withdrawal_initiated_date'] = __('Withdrawal Initiate Date', 'mlm');
			    $listArr[-1]['withdrawal_initiated_comment'] = __('Withdrawal Comment', 'mlm');
			    $listArr[-1]['netamount'] = __('Amount', 'mlm');
			    $listArr[-1]['payout_id'] = __('Payout Id', 'mlm');
			    $listArr[-1]['payoutDate'] = __('Payout Date', 'mlm');

		    $i = 0;
		    $payoutDetail = array();
		    ?>
		<table class="table" border="1">
			<thead>
				<tr>
					<th>Member Username</th><th>Member Email</th>
					<th>Withdrawal Initiate Date</th><th>Withdrawal Comment</th>
					<th>Amount</th><th>Payout Id</th>
					<th>Payout Date</th><th>Action</th>
				</tr>
			</thead>
		    <?php
		    if ($wpdb->num_rows > 0) {
	        	foreach ($rs as $row) {
	            $sql1 = "SELECT {$wpdb->prefix}binarymlm_users.username AS uname , {$wpdb->prefix}users.user_email AS uemail FROM {$wpdb->prefix}users,{$wpdb->prefix}binarymlm_users WHERE {$wpdb->prefix}binarymlm_users.username = {$wpdb->prefix}users.user_login AND {$wpdb->prefix}binarymlm_users.id = '" . $row['user_id'] . "'";

	            $row1 = $wpdb->get_row($sql1, ARRAY_A);

		            $payoutDetail['memberId'] = $row['user_id'];
		            $payoutDetail['name'] = $row1['uname'];
		            $payoutDetail['email'] = $row1['uemail'];
		            $payoutDetail['payoutId'] = $row['payout_id'];
		            $payoutDate = date_create($row['payoutDate']);
		            $payoutDetail['payoutDate'] = date_format($payoutDate, $date_format);
		            $withdrawal_date = date_create($row['withdrawal_initiated_date']);
		            $payoutDetail['widate'] = date_format($withdrawal_date, $date_format);
		            $payoutDetail['wicomment'] = $row['withdrawal_initiated_comment'];
		            $payoutDetail['commamount'] = $row['commission_amount'];
		            $payoutDetail['refcommamt'] = $row['referral_commission_amount'];
		            $payoutDetail['bonusamount'] = $row['bonus_amount'];
		            $payoutDetail['total_amt'] = $row['total_amt'];
		            $payoutDetail['capped_amt'] = $row['capped_amt'];
		            $payoutDetail['cap_limit'] = $row['cap_limit'];
		            $payoutDetail['servicecharges'] = $row['service_charge'];
		            $payoutDetail['tax'] = $row['tax'];
		            $payoutDetail['netamount'] = number_format($row['capped_amt'], 2);
			?>
			<tbody>
				<tr>
					<td><?php echo $payoutDetail['name']; ?></td>
					<td><?php echo $payoutDetail['email']; ?></td>
					<td><?php echo $payoutDetail['widate']; ?></td>
					<td><?php echo $payoutDetail['wicomment']; ?></td>
					<td><?php echo $payoutDetail['netamount']; ?></td>
					<td><?php echo $payoutDetail['payoutId']; ?></td>
					<td><?php echo $payoutDetail['payoutDate']; ?></td>
					<td><form name='withdrawal_process' method='POST' action='<?php admin_url('admin.php') ?>?page=binarymlm-admin-withdrawal-process' id='withdrawal_process'>
						<input type='hidden' name='member_name' value='<?php echo $payoutDetail['name']; ?>'>
						<input type='hidden' name='member_id' value='<?php echo $payoutDetail['memberId']; ?>'>
						<input type='hidden' name='member_payout_id' value='<?php echo $payoutDetail['payoutId']; ?>'>
						<input type='hidden' name='member_email' value='<?php echo $payoutDetail['email']; ?>'>
						<input type='hidden' name='withdrawal_amount' value='<?php echo $payoutDetail['netamount']; ?>'>
						<input type='submit' value='Process' id='process' name='process-amount'>
						</form>&nbsp;|&nbsp;<a class='ajax-link' id='<?php echo $payoutDetail['memberId']; ?>$<?php echo $payoutDetail['payoutId']; ?>' href='javascript:void(0);'>Delete</a>

					</td>
				</tr>
			</tbody>
		</table></div>
		<?php

            $listArr[$i]['name'] = $payoutDetail['name'];
            $listArr[$i]['email'] = $payoutDetail['email'];
            $listArr[$i]['withdrawal_initiated_date'] = $payoutDetail['widate'];
            $listArr[$i]['withdrawal_initiated_comment'] = $payoutDetail['wicomment'];
            $listArr[$i]['netamount'] = $payoutDetail['netamount'];
            $listArr[$i]['payout_id'] = $payoutDetail['payoutId'];
            $listArr[$i]['payoutDate'] = $payoutDetail['payoutDate'];
            $i++;
        }

        $value = serialize($listArr);
        ?>
        <form method="post" action="<?php echo plugins_url() ?>/binarymlm/includes/admin/export-file.php">
            <input type="hidden" name ="listarray" value='<?php echo $value ?>' />
            <input type="hidden" name ="filename" value='pending-withdrawal-list-report' />
			<button type="submit" name="export_csv" id="export_csv" class="btn btn-primary btn-default">Export to CSV &raquo;</button>
        </form></div></div>
            <?php
        }
        else {
            _e("Nothing to withdraw here!", 'mlm');
        }
        ?>

	    <script type="text/javascript">
	        jQuery(document).ready(function($) {
	            $(".ajax-link").click(function() {
	                var b = $(this).parent().parent();
	                var id = $(this).attr('id');
	                var ids = id.split("$");
	                var dataString = 'withdrawaldel_id=' + ids[0] + '&payoutid=' + ids[1]+'&action=withdrawal_delete';

	                if (confirm("<?php _e('Confirm Delete withdrawal request?', 'mlm') ?>")) {
	                    $.ajax({
	                        type: "POST",
	                        url: "<?php _e($ajax_url); ?>",
	                        data: dataString,
	                        cache: false,
	                        success: function(e)
	                        {
	                            //b.hide();
	                            window.location.reload(true);
	                        }
	                    });
	                    return false;
	                }
	            });
	        });
	    </script>
<?php } 
}
