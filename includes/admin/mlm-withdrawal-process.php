<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Withdrawal_process{
	public function mlm_withdrawal_process() {
    include_once ABSPATH . '/wp-admin/includes/plugin.php';
    if (is_plugin_active('mlm-paypal-mass-pay/load-data.php')) {
        //plugin is activated
        $this->mlm_withdrawal_process_MASS_Active();
    }
    else {
        $this->mlm_withdrawal_process_MASS_Inactive();
    }
}

function mlm_withdrawal_process_MASS_Inactive() {
    global $wpdb;
    ?>
    <div class='wrap'>
        <div id="icon-users" class="icon32"></div><h1>Withdrawal Process</h1>

    <?php
    if (isset($_POST['member_name'])) {
        $mname = $_POST['member_name'];
        $mid = $_POST['member_id'];
        $mpid = $_POST['member_payout_id'];
        $memail = $_POST['member_email'];
        $wamount = $_POST['withdrawal_amount'];

        if (isset($_POST['paydone'])) {
            $payout_id = $_POST['payout_id'];
            $user_id = $_POST['user_id'];

            if (!empty($_POST['cheque_no']))
                $cheque_no = $_POST['cheque_no'];
            else
                $cheque_no = '';
            if (!empty($_POST['cheque_date']))
                $cheque_date = $_POST['cheque_date'];
            else
                $cheque_date = '';
            if (!empty($_POST['cbank_name']))
                $bank_name = $_POST['cbank_name'];
            else
                $bank_name = '';

            if (!empty($_POST['btbank_name']))
                $user_bank_name = $_POST['btbank_name'];
            else
                $user_bank_name = '';
            if (!empty($_POST['btaccount_no']))
                $user_bank_account_no = $_POST['btaccount_no'];
            else
                $user_bank_account_no = '';
            if (!empty($_POST['bt_code']))
                $banktransfer_code = $_POST['bt_code'];
            else
                $banktransfer_code = '';
            if (!empty($_POST['bt_benificiary']))
                $beneficiary = $_POST['bt_benificiary'];
            else
                $beneficiary = '';
            if (!empty($_POST['pmode']))
                $payment_mode = $_POST['pmode'];
            else
                $payment_mode = '';
            if (!empty($_POST['specified']))
                $comment = $_POST['specified'];
            else
                $comment = '';

            $sql = "UPDATE {$wpdb->prefix}binarymlm_payout SET `payment_mode`='" . $payment_mode . "',`cheque_no`='" . $cheque_no . "',
		`cheque_date`='" . $cheque_date . "',`bank_name`='" . $bank_name . "',`banktransfer_code`='" . $banktransfer_code . "', 
		`user_bank_name`='" . $user_bank_name . "',`user_bank_account_no`='" . $user_bank_account_no . "',
		`beneficiary`='" . $beneficiary . "',`payment_processed`='1',`payment_processed_date`= NOW(), 
		`other_comments`='" . $comment . "' WHERE `payout_id`= '" . $payout_id . "' AND `user_id`= '" . $user_id . "'";

            $res = $wpdb->query($sql);
            if ($res == '1') {
                $msg = "<div class='notibar msgsuccess'><p>Your Withdrawal is successfully processed.</p></div>";
                echo $msg;
                ?>
                <script>window.location.href = "<?php echo site_url() . '/wp-admin/admin.php?page=binarymlm-admin-pending-withdrawal' ?>"</script>
                <?php
                if ($u = get_option('letscms_mlm_process_withdrawal_mail', true) == 1) {
                    $this->WithDrawalProcessedMail($mid, $payment_mode, $wamount, $payout_id);
                }
            }
        }
    }
    ?>
    <div class="container1" >
        <div class="box1" ><h4 align="center">Process Individual User Withdrawal</h4></div>
        <div class="wrap2">
        <form method="POST" action="" id="paydone_form" name="payment_complete">

        <div class="col-md-12">
            <div class="form-group">
                <label>Member Id</label>
                <input type="text" class="form-control" name="member_id" id="mid" size="40" value="<?php if (!empty($mid)) _e($mid); ?>" readonly>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Member User Name</label>
                <input type="text" class="form-control" name="member_name" id="mname" size="40" value="<?php if (!empty($mname)) _e($mname); ?>" readonly>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Payout Id</label>
                <input type="text" class="form-control" name="member_payout_id" id="mpid" size="40" value="<?php if (!empty($mpid)) _e($mpid); ?>" readonly>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Member Email</label>
                <input type="text" class="form-control" name="member_email" id="memail" size="40" value="<?php if (!empty($memail)) _e($memail); ?>" readonly>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="withdrawal_amount" id="wamount" size="40" value="<?php if (!empty($wamount)) _e($wamount); ?>" readonly>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Payment Mode</label>
                <input name="pmode" class="radio" type="radio" checked="checked" value="cash"><?php _e('Cash', 'mlm') ?><br/>
                        <input name="pmode" type="radio" value="cheque"><?php _e('Cheque', 'mlm') ?><br/>
                        <div class="ptype" id="mode-cheque" name="cheque_info" style="display:none;padding:6px 0px;">
                            <input type="text" name="cheque_no" id="cno" value="" placeholder="Cheque Number" disabled="disabled" required>
                            <input type="date" name="cheque_date" id="cdate" value="" placeholder="Cheque Date" disabled="disabled" required>
                            <input type="text" name="cbank_name" id="cbname" value="" placeholder="Bank Name" disabled="disabled" required>
                        </div>
                        <input name="pmode" type="radio" value="bank-transfer"><?php _e('Bank Transfer', 'mlm') ?><br/>
                        <div class="ptype" id="mode-bank-transfer" name="bank-transfer_info" style="display:none;padding:6px 0px;">
                            <input type="text" name="bt_benificiary" id="btbe" value="" placeholder="Beneficiary Name" disabled="disabled" required>
                            <input type="text" name="btaccount_no" id="btano" value="" placeholder="Account Number" disabled="disabled" required>
                            <input type="text" name="btbank_name" id="btbname" value="" placeholder="Bank Name" disabled="disabled" required>
                            <input type="text" name="bt_code" id="btcode" value="" placeholder="Bank Transfer Code" disabled="disabled">
                        </div>
                        <input name="pmode" type="radio" value="other"><?php _e('Other', 'mlm') ?><br/>
                        <div class="ptype" id="mode-other" name="other" style="display:none;padding:6px 0px;">
                            <input type="text" name="specified" size="30" disabled="disabled" required>
                        </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <input type='hidden' name='payout_id' value='<?php _e($mpid); ?>'>
                <input type='hidden' name='user_id' value='<?php _e($mid); ?>'>
                <button type="submit" name="paydone" id="paydone" class="btn btn-primary btn-default" >Process &raquo;</button>
            </div>
        </div>
</form>
    </div></div></div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("input[name='pmode']").click(function() {
                var method = $(this).val();
                $("div.ptype").hide();
                $("#mode-" + method).show();
                if (method == 'cheque') {
                    $("#mode-" + method + " input").removeAttr('disabled');
                    $("#mode-bank-transfer input").attr('disabled', 'disabled');
                    $("#mode-other input").attr('disabled', 'disabled');
                }
                else if (method == 'bank-transfer') {
                    $("#mode-" + method + " input").removeAttr('disabled');
                    $("#mode-cheque input").attr('disabled', 'disabled');
                    $("#mode-other input").attr('disabled', 'disabled');
                }
                else if (method == 'other') {
                    $("#mode-" + method + " input").removeAttr('disabled');
                    $("#mode-cheque input").attr('disabled', 'disabled');
                    $("#mode-bank-transfer input").attr('disabled', 'disabled');
                }
                else {
                    $("#mode-bank-transfer input").attr('disabled', 'disabled');
                    $("#mode-cheque input").attr('disabled', 'disabled');
                    $("#mode-other input").attr('disabled', 'disabled');
                }

            });
        });
    </script>

<?php } 
}
