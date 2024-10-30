<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Earning_Report{
	use letscms_mlmfunction;
    public function earning_report(){
	$this->letscms_check_first_user();
    global $wpdb,$date_format;

    extract($_REQUEST);
    if (isset($datefrom) && !empty($datefrom)) {
        $datefrom1 = explode("-", $datefrom);
        $datefromfinal = $datefrom1[0] . '-' . $datefrom1[1] . '-' . $datefrom1[2] . ' 00:00:00';
        $timestamp = mktime(0, 0, 0, $datefrom1[1], $datefrom1[2], $datefrom1[0]);
        $month_name = date("F", $timestamp);
        $day = date("dS", $timestamp);

        $from = $day . ' ' . $month_name . ',' . $datefrom1[0];
    }
    else {
        $year = date('Y');
        $month = date('m');
        $day = '01';
        $datefromfinal = $year . '-' . $month . '-' . $day . ' 00:00:00';
        $timestamp = mktime(0, 0, 0, $month);
        $month_name = date("F", $timestamp);
        $from = $day . 'st ' . $month_name . ',' . $year;
    }

    if (isset($dateto) && !empty($dateto)) {
        $dateto1 = explode("-", $dateto);
        $datetofinal = $dateto1[0] . '-' . $dateto1[1] . '-' . ($dateto1[2] . ' 23:59:59');
        $timestamp = mktime(0, 0, 0, $dateto1[1], $dateto1[2], $dateto1[0]);
        $month_name = date("F", $timestamp);
        $day = date("dS", $timestamp);
        
        $to = $day . ' ' . $month_name . ',' . $dateto1[0];
    }
    else {
        $year = date('Y');
        $month = date('m');
        $day1 = date('d');
        $day = date('dS');
        $datetofinal = $year . '-' . $month . '-' . ($day1 . ' 23:59:59');
        $timestamp = mktime(0, 0, 0, $month);
        $month_name = date("F", $timestamp);
        $to = $day . ' ' . $month_name . ',' . $year;
    }
    if (isset($datefromfinal) && isset($datetofinal)) {
        $between = "AND wu.user_registered BETWEEN '$datefromfinal' AND '$datetofinal'";
        $between1 = "WHERE date BETWEEN '$datefromfinal' AND '$datetofinal'";
        $date_used = "AND date_used BETWEEN '$datefromfinal' AND '$datetofinal'";
    }
    else {
        $between = '';
        $between1 = '';
        $date_used = '';
    }

$sql1="SELECT SUM(product_price) as total from {$wpdb->prefix}binarymlm_users as mu INNER JOIN {$wpdb->prefix}users as wu ON mu.user_id = wu.ID WHERE payment_status='1' $between";
$total_product_price = $wpdb->get_var($sql1);

$sql2="SELECT sum(total_amt) as amount from {$wpdb->prefix}binarymlm_payout $between1";
$payout_paid = $wpdb->get_var($sql2);

if (isset($payout_paid)) {
    $payout_paid = $payout_paid;
}
else {
    $payout_paid = '0';
}


if(!empty($total_product_price)){
    $total_amount = $total_product_price;
} else{
    $total_amount = '0';
}
$net_earning = $total_amount - $payout_paid;
?>

<script type="text/javascript">
    var popup1, popup2, splofferpopup1;
    var bas_cal, dp_cal11, dp_cal2, ms_cal; // declare the calendars as global variables 
    window.onload = function() {
        dp_cal11 = new Epoch('dp_cal11', 'popup', document.getElementById('datefrom'));
        dp_cal12 = new Epoch('dp_cal12', 'popup', document.getElementById('dateto'));
    };
</script>
<div class="wrap">
<div class="container1" >
    <div class="box1" ><h4 align="center">Earning Reports</h4></div>
        <div class="wrap2">
            <form id="processed_report" method="GET" action="">
                <div class="col-md-12"><div class="row">

                    <div class="col-md-6">
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                        <input type="hidden" name="tab" value="earningreports" />
                        <label>Date From</label>
                        <input class="form-control" type="date" name="datefrom" id="datefrom">
                    </div>
                    <div class="col-md-6">
                        <label>Date To</label>
                        <input class="form-control" type="date" name="dateto" id="dateto">
                    </div>
                </div><br>
                <div class="col-md-12">
                    <button type="submit" name="submit" id="submit" class="btn btn-primary btn-default">Go &raquo;</button>
                    <button type="reset" name="reset" id="reset" class="btn btn-light" onclick="window.location = '<? admin_url() ?>'admin.php / ?page = admin - mlm - reports & tab = earningreports">Reset</button>
                </div>
                </div>
            </form><br>
<div class="col-md-12">
    Period: from &nbsp; <strong><?php echo $from; ?></strong> &nbsp;&nbsp; to  &nbsp; <strong><?php echo $to; ?></strong>
</div>
<div>&nbsp;</div>
<table class="table" cellspacing="0" class="wp-list-table widefat fixed toplevel_page_admin-mlm-reports">

    <tr><td><strong><?php _e('Gross Earnings', 'mlm'); ?> :&nbsp;&nbsp;<?php echo $total_amount; ?></strong><br/><br/></td></tr>
    <tr><td><strong><?php _e('Payouts', 'mlm'); ?> :&nbsp;&nbsp;<?php echo $payout_paid; ?></strong><br/><br/></td></tr>
    <tr><td><strong><?php _e('Net Earnings', 'mlm'); ?>:&nbsp;&nbsp;<?php echo $net_earning; ?></strong><br/><br/></td></tr>

</table></div></div></div>

<?php
}
}
