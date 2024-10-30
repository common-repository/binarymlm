<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Payout_Reports{
use letscms_mlmfunction;
  
  /***********************************Payout Reports***********************************************/
  public function mlmPayoutReports(){
 	global $wpdb;
 	$this->letscms_check_first_user();
    require_once('mlm-payout-list-table.php');
    $objpayouts = new PayoutReport_List_Table();
    $objpayouts->prepare_items();
    ?>

    <div class='wrap'>
        <div id="icon-users" class="icon32"></div>
        
        <div class="container1" >
            <div class="box1" ><h4 align="center">Payout Report</h4></div><br>
                <form id="processed_report" name="myform" method="GET" action="">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <input type="hidden" name="tab" value="payoutreport" />
        <?php
        if (empty($_GET['id'])) {
            $objpayouts->display();
        }
        else {
            $payout_id = $_GET['id'];
            $results = $wpdb->get_results("select * from {$wpdb->prefix}binarymlm_commission where payout_id='$payout_id'");

            foreach ($results as $result) {
                $pkey = $wpdb->get_var("select user_key from {$wpdb->prefix}binarymlm_users where id='$result->parent_id'");
                $unames = explode(',', $result->child_ids);
                foreach ($unames as $uname) {
                    $ukey = $wpdb->get_var("select user_key from {$wpdb->prefix}binarymlm_users where username='$uname'");
                    $wpdb->query("update {$wpdb->prefix}binarymlm_leftleg set payout_id='$payout_id' where pkey='$pkey' and ukey='$ukey' and commission_status='1'");
                    $wpdb->query("update {$wpdb->prefix}binarymlm_rightleg set payout_id='$payout_id' where pkey='$pkey' and ukey='$ukey' and commission_status='1'");
                }
            }
            ?>

            <table class="table">
                <thead><tr>
                    <th>S.No</th>
                    <th>Ref.</th>
                    <th>Full Name</th>
                    <th>Referral Commission</th>
                    <th>Pair Commission</th>
                    <th>Bonus</th>
                    <th>Amount</th>
                    <th>Pairs</th>
                    <th>Downline</th>
                </tr></thead>
                <?php
                $id = $_GET['id'];
                $sql = "select * from {$wpdb->prefix}binarymlm_payout where payout_id = $id";
                $results = $wpdb->get_results($sql, ARRAY_A);
                $i = 0;
                $per_page = 5;
                foreach ($results as $row) {
                    $i++;
                    $user_id = $row['user_id'];
                    $query = "SELECT user_id,user_key,username from {$wpdb->prefix}binarymlm_users where id = '$user_id'";
                    $rows = $wpdb->get_row($query,ARRAY_A);
                    $firstname = get_user_meta($rows['user_id'], 'first_name', true);
                    $lastname = get_user_meta($rows['user_id'], 'last_name', true);
                    $user_key = $rows['user_key'];
                    $username = $rows['username'];
                    $record = $this->calculatelegUsersByPayoutId($user_key, $payout_id);
                    ?>
                    <tbody><tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $username; ?></td>
                        <td><?php echo $firstname . ' ' . $lastname ?></td>
                        <td><?php echo $row['referral_commission_amount']; ?></td>
                        <td><?php echo $row['commission_amount']; ?></td>
                        <td><?php echo $row['bonus_amount']; ?></td>
                        <td><?php echo $row['total_amt']; ?></td>
                        <td><?php echo $record['p']; ?></td>
                        <td><strong>Left:</strong><?php echo $record['l'] . '<br />' ?><strong>Right:</strong><?php echo $record['r'] ?></td>
                    </tr></tbody>
                    <?php
                }
                ?>
            </table>
            <div>
            <button type="button" name="back" id="back" class="btn btn-primary btn-default" onclick="window.history.back()"> &laquo; Back</button>  </div>
                <?php
            }
            ?>
    </form>	</div></div>
    <script language='JavaScript' type='text/javascript'>
        var frmvalidator = new Validator('myform');
        //frmvalidator.addValidation('datefrom','req', 'Please enter from date');

    </script>
    <?php
    extract($_REQUEST); 

    $sql = "SELECT * FROM {$wpdb->prefix}binarymlm_payout_master ORDER BY date DESC";
    $rs = $wpdb->get_results($sql, ARRAY_A);
    $i = 0;
    $listArr = array();
    $listArr[-1]['id'] = __('Payout ID', 'mlm');
    $listArr[-1]['date'] = __('Payout Date', 'mlm');
    $listArr[-1]['View'] = __('Details', 'mlm');

    $num = $wpdb->num_rows;
    if ($num > 0) {
        foreach ($rs as $row) {
            $listArr[$i]['id'] = $row['id'];
            $listArr[$i]['date'] = $row['date'];
            $listArr[$i]['View'] = $row['id'];
            $i++;
        }
    }

    $value = serialize($listArr);
  }
}
