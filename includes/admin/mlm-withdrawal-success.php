<?php
defined( 'ABSPATH' ) || exit;
class Letscms_MLM_Withdrawal_Success{
	public function mlm_withdrawal_sucess() {
        ?>
 	<div class='wrap'>
        <div id="icon-users" class="icon32"></div>
        
        <div class="container1" >
            <div class="box1" ><h4 align="center">Processed Withdrawals Report</h4></div>
            <div class="wrap2">
   

    <?php
    require_once('mlm-withdrawal-list-table.php');
    $objOrderList = new Withdrawals_List_Table();
    $objOrderList->prepare_items();
    $objOrderList->display();


    $listArr = get_option('letscms_mlm_sucessed_withdrawal_list');

    $value = serialize($listArr);
    ?>
    <form method="post" action="<?php echo plugins_url() ?>/binarymlm/includes/admin/export-file.php">
        <input type="hidden" name ="listarray" value='<?php echo $value ?>' />
        <input type="hidden" name ="filename" value='sucessed-withdrawal-list-report' />
        <button type="submit" name="export_csv" id="export_csv" class="btn btn-primary btn-default">Export to CSV &raquo;</button>
        </form></div></div></div>
        <?php
	}
}
