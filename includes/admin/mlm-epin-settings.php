<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_ePin_Settings{
  use letscms_mlmfunction;
  public function epin_tab(){
	global $wpdb;
	$this->letscms_check_first_user();
    $mlm_settings = get_option('letscms_mlm_general_settings');
    $pro_price_settings = $wpdb->get_results("select * from {$wpdb->prefix}binarymlm_product_price where p_id!='1'");

    if (isset($_POST['binarymlm_epin_save'])) {
        update_option('letscms_mlm_epin_settings', $_POST);
        $epin_settings = get_option('letscms_mlm_epin_settings');

        $epin_length = $mlm_settings['binarymlm_epin_length'];

        if ($_POST['binarymlm_epins_type'] == '1') {
            $type = 'regular';
        }
        else {
            $type = 'free';
        }

        $range = $epin_settings['binarymlm_number_of_epins'];
        $epins_type = $epin_settings['binarymlm_epins_type'];


        $epin_value = $epin_settings['binarymlm_epins_value'];


        for ($i = 0; $i <= $range - 1; $i++) {
            $epin_no = $this->epin_genarate($epin_length);
            //if generated key is already exist in the DB then again re-generate key
            do {
                $check = $wpdb->get_var("SELECT COUNT(*) ck FROM {$wpdb->prefix}binarymlm_epins WHERE `epin_no` = '" . $epin_no . "'");
                $flag = 1;
                if ($check == 1) {
                    $epin_no = $this->epin_genarate($epin_length);
                    $flag = 0;
                }
            } while ($flag == 0);


            if ($type == 'regular') {
                $p_id = $_POST['binarymlm_epins_value'];
            }
            else {
                $p_id = 1;
            }
            $query = "insert into {$wpdb->prefix}binarymlm_epins set epin_no='$epin_no',point_status='$epins_type',p_id='" . $p_id . "', date_generated=now();";

            $wpdb->query($query);
            if ($i == $range - 1) {
                $message = 1;
            }
            else {
                $message = 0;
            }
        }
    }
    else {
        $epin_settings = get_option('letscms_mlm_epin_settings');
    }
    ?>

	<script>

        function epinValidation()
        {
            var number_of_epins = document.getElementById("binarymlm_number_of_epins").value;
            if (number_of_epins == "")
            {
                alert('<?php _e("The Number of ePins can not be empty. please Specify the ePins Number.", "mlm") ?>');
                document.getElementById('binarymlm_number_of_epins').focus();
                return false;
            }
        }

        function epinType(val) {

            if (val == '0')
            {
                document.getElementById("binarymlm_epins_value").disabled = true;
            }
            else {
                document.getElementById("binarymlm_epins_value").disabled = false;
            }

        }

    </script>
    <div class="wrap1">        

        <div><?php if (isset($message) && $message == 1) {
        ?><div class='notibar msgsuccess'><p>
                <?php echo $epin_settings['binarymlm_number_of_epins']; ?> 
                <?php _e('ePins of type ', 'mlm'); ?>
                <?php
                if (isset($epin_settings['binarymlm_epins_type']) && $epin_settings['binarymlm_epins_type'] == '1') {
                    echo 'Regular';
                }
                else {
                    echo 'Free';
                }
                ?>
                <?php _e(' have been generated successfully.', 'mlm'); ?>

                    <br>
                    <?php _e('Click', 'mlm'); ?>&nbsp;<a href="<?php echo admin_url() . "admin.php?page=binarymlm-admin-reports&tab=epinreports" ?>"><?php _e('<strong>Here</strong>', 'mlm'); ?></a>&nbsp;
                    <?php _e('to go to the ePin Report to see the listing of generated ePins.', 'mlm'); ?></p>
                </div>
                <?php
            }
            else if (isset($message) && $message == 0) {
                ?>
                <span style="color:#D8000C;"><?php _e('There was an error in generating the ePins. Please try again.', 'mlm'); ?></span><?php } ?></div>
        
<div class="container1" >
<div class="box1" ><h4 align="center">Binary MLM ePin settings</h4></div>
        <div class="wrap2">
        <form role="form"  method="post" action="#" id="form" onSubmit="return epinValidation();">
        <div class="col-md-12">
        <div class="col-md-12">
        <div class="form-group">
            <label><strong><?php _e('Type :', 'mlm'); ?></strong> </label>

            <?php $selected = 'selected="selected"'; ?>
            <select class="form-control" name="binarymlm_epins_type" id="binarymlm_epins_type" onchange="epinType(this.value);">
                    <option value="1" <?php
                    if (isset($epin_settings['binarymlm_epins_type']) && $epin_settings['binarymlm_epins_type'] == '1') {
                        echo $selected;
                    }
                    else {
                        echo '';
                    }
                    ?>><?php _e("Regular", "mlm") ?></option>
                    <option value="0" <?php
                    if (isset($epin_settings['binarymlm_epins_type']) && $epin_settings['binarymlm_epins_type'] == '0') {
                        echo $selected;
                    }
                    else {
                        echo '';
                    }
                    ?>><?php _e("Free", "mlm") ?></option>
            </select>
        </div>
        </div>

        <div class="col-md-12">
        <div class="form-group">
            <label>  <strong><?php _e('ePins Value', 'mlm'); ?></strong></label>

            <select class="form-control" name="binarymlm_epins_value" id="binarymlm_epins_value">
                    <?php foreach ($pro_price_settings as $pricedetail) { ?>       
                        <option value="<?php echo $pricedetail->p_id ?>"><?php echo $pricedetail->product_name ?> (<?php echo $mlm_settings['binarymlm_currency'] . ' ' . $pricedetail->product_price; ?>)</option>
                    <?php } ?>
            </select>
        </div>
        </div>

        <div class="col-md-12">
        <div class="form-group">
            <label><strong><?php _e('No. of ePins', 'mlm'); ?></strong></label>
            <input class="form-control" type="text" name="binarymlm_number_of_epins" id="binarymlm_number_of_epins" />
        </div>
        </div>

        <div class="col-md-12">
        <div class="form-group">
        <button type="submit" name="binarymlm_epin_save" id="binarymlm_epin_save" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Generate ePins &raquo;</button>
        </div>
        </div>

        </div>
        </form></div></div>
<?php
  }
}// end of class
