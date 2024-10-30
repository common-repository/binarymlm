<?php
defined( 'ABSPATH' ) || exit;
class Letscms_MLM_Reset_Settings{
  use letscms_mlmfunction;
  public function adminMlmResetAllData(){
	$this->letscms_check_first_user();
	$url = plugins_url();
?>
    <div class='wrap1'>
        
<?php
$adminajax= "'".admin_url('admin-ajax.php')."'";
?>
  <div class="container1">
    <div class="box1" ><h4 align="center">Reset All Binary MLM Data</h4></div><br>
    <div style="margin-left:20%">
        <button type="submit" name="reset_data" id="reset_data" class="btn btn-primary btn-default" onclick="reset_all_data(<?php echo $adminajax; ?>)">Reset Data &raquo;</button>

    </div> 
    <div id="resetmessage"></div>
      
  </div>
 

        <?php
  }
}// end of class
