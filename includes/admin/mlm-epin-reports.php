<?php
defined( 'ABSPATH' ) || exit; 
class Letscms_MLM_ePin_Reports{
use letscms_mlmfunction;
  
  /***********************************ePin Reports***********************************************/
  public function mlmePinReports(){
	global $wpdb;
	$this->letscms_check_first_user();
    require_once('mlm-epin-list-table.php');
    $objEpinList = new EpinReports_List_Table();
    $objEpinList->prepare_items();

    extract($_REQUEST);
    if (isset($page)) {
        $url = 'page=' . $page;
    }

    if (isset($epin_status)) {
        $url.='&epin_status=' . $epin_status;
    }
    else {
        $url.='';
    }
    $epin_status1 = isset($epin_status) ? $epin_status : '';

    $epin_value1 = isset($epin_value) ? $epin_value : '';
    ?>

    <div class='wrap'>

        <div id="icon-users" class="icon32"></div>

            <div class="container1" >
            <div class="box1" ><h4 align="center">ePin Report</h4></div>
            <div class="wrap2">
    <?php
    $mlm_settings = get_option('letscms_mlm_general_settings');

    if (isset($mlm_settings['binarymlm_ePin_activate']) && $mlm_settings['binarymlm_ePin_activate'] == '1') {
        ?>
        <div style="margin-left:10px;float:left;text-decoration:none;">
            
            <a href="<?php echo admin_url() . "admin.php?page=" . $_REQUEST['page'] . "&tab=epinreports" ?>"><button class="btn btn-light"><?php _e('Reset', 'mlm'); ?></button></a>
        </div>	

        <div style="margin-right:10px;float:right">			
            <form action="" method="get" style="float:right;">
                <input type="hidden" name="page" value="binarymlm-admin-reports" />
                <input type="hidden" name="tab" value="epinreports" />
                <input type="hidden" name="epin_status" value="<?php echo $epin_status1 ?>" />
                <input type="hidden" name="epin_value" value="<?php echo $epin_value1 ?>" />
                <input type="text" name="search"/>
                <button type="submit" name="Search" id="Search" class="btn btn-light">Search &raquo;</button>
            </form>
        </div>

        <form id="project-filter" method="GET" action="">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <input type="hidden" name="tab" value="epinreports" />

            <?php
            $objEpinList->display();
            ?>
        </form>	

        <?php
        if (!isset($epin_status) && !isset($search) && !isset($epin_value)) {
            $sql = "SELECT * FROM {$wpdb->prefix}binarymlm_epins ORDER BY id ASC";
        }
        else {

            if (isset($epin_status) && $epin_status != '' && isset($epin_value) && $epin_value != '' && !empty($search)) {
                $amt = $epin_value;
                $where = " WHERE status='" . $epin_status . "' AND  p_id='" . $amt . "' AND epin_no like '%" . trim($search) . "%'";
            }
            else if (isset($epin_status) && $epin_status != '' && isset($epin_value) && $epin_value != '') {
                $amt = $epin_value;
                $where = " WHERE status='" . $epin_status . "' AND  p_id='" . $amt . "'";
            }
            else if (isset($epin_value) && $epin_value != '' && !empty($search)) {
                $amt = $epin_value;
                $where = " WHERE  p_id='" . $amt . "' AND epin_no like '%" . trim($search) . "%'";
            }
            else if (isset($epin_status) && $epin_status != '' && !empty($search)) {

                $where = " WHERE  status='" . $epin_status . "' AND epin_no like '%" . trim($search) . "%'";
            }
            else if (isset($epin_status) && $epin_status != '') {
                $where = " WHERE  status='" . $epin_status . "' ";
            }
            else if (isset($epin_value) && $epin_value != '') {
                $amt = $epin_value;
                $where = " WHERE  p_id='" . $amt . "' ";
            }
            else if (!empty($search)) {
                $where = " WHERE  epin_no like '%" . trim($search) . "%' ";
            }

            $sql = "SELECT * FROM {$wpdb->prefix}binarymlm_epins  $where  ORDER BY id ASC";
        }

        $rs = $wpdb->get_results($sql, ARRAY_A);

        $i = 0;
        $listArr = array();
        $listArr[-1]['epin'] = __('Pin No.', 'mlm');
        $listArr[-1]['epinprice'] = __('Pin Price', 'mlm');
        $listArr[-1]['username'] = __('User Name', 'mlm');
        $listArr[-1]['firstname'] = __('First Name', 'mlm');
        $listArr[-1]['lastname'] = __('Last Name', 'mlm');
        $listArr[-1]['type'] = __('Type', 'mlm');
        $listArr[-1]['genarated_on'] = __('Generated On', 'mlm');
        $listArr[-1]['date_used'] = __('Used Date', 'mlm');
        $num = $wpdb->num_rows;
        if ($num > 0) {
            foreach ($rs as $row) {
                $user_id = $this->letscms_getuseruidbykey($row['user_key']);
                $firstname = get_user_meta($user_id, 'first_name', true);
                $lastname = get_user_meta($user_id, 'last_name', true);
                $genaral_date = get_option('links_updated_date_format');

                if ($row['date_used'] == '0000-00-00 00:00:00') {
                    $used_date = '';
                }
                else {
                    $used_date = date("$genaral_date", strtotime($row['date_used']));
                }

                if ($row['date_generated'] == '0000-00-00 00:00:00') {
                    $genarated_on = '';
                }
                else {
                    $genarated_on = date("$genaral_date", strtotime($row['date_generated']));
                }
                $price = $wpdb->get_var("select product_price from {$wpdb->prefix}binarymlm_product_price where p_id='" . $row['p_id'] . "'");
                $type = $row['point_status'] == '1' ? 'Regular' : 'Free';
                $listArr[$i]['epin'] = $row['epin_no'];
                $listArr[$i]['epinprice'] = $price;
                $listArr[$i]['username'] = $this->letscms_mlmGetUserNameByKey($row['user_key']);
                $listArr[$i]['firstname'] = $firstname;
                $listArr[$i]['lastname'] = $lastname;
                $listArr[$i]['type'] = $type;
                $listArr[$i]['genarated_on'] = $genarated_on;
                $listArr[$i]['date_used'] = $used_date;

                $i++;
            }
        }
 
        $value = serialize($listArr);
        ?>
        <form method="post" action="<?php echo plugins_url() ?>/binarymlm/includes/admin/export-file.php">
            <input type="hidden" name ="listarray" value='<?php echo $value ?>' />
            <input type="hidden" name ="filename" value='epin-report' />
            <button type="submit" name="export_csv" id="export_csv" class="btn btn-primary btn-default">Export to CSV &raquo;</button>
        </form>
        <?php
    }
    else {
        ?><div style="padding: 20px;margin: 0 auto;">
            <?php _e('It seems you have not activated the ePin functionality under Settings -> General. ePin Report is accessible only with that setting set to Yes.', 'mlm'); ?>
            <br>
            <?php _e('Click', 'mlm'); ?>&nbsp;<a href="<?php echo admin_url() . "admin.php?page=binarymlm-admin-settings" ?>"><?php _e('<strong>Here</strong>', 'mlm'); ?></a>&nbsp;
            <?php _e(' to go to Settings -> General and activate the ePin functionality.', 'mlm'); ?>
        </div>
        <?php
    } ?>
    </div></div>
<?php
  }
}
