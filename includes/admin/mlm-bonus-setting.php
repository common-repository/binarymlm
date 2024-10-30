<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Bonus_Settings
{
  use letscms_mlmfunction;
  
  /***********************************Bonus Settings***********************************************/
  public function mlmBonus(){
    $error = array();
    $chk = 'error';
    $this->letscms_check_first_user();
    //most outer if condition
    if(isset($_POST['binarymlm_bonus_settings']))
    {
      $bonus_criteria = sanitize_text_field( $_POST['binarymlm_bonus_criteria'] );
      $pair =  $_POST['binarymlm_pair'];
      $amount = $_POST['binarymlm_amount'];

      if ( $this->letscms_checkInputField($bonus_criteria) )
        $error[] = __("Please Select bonus criteria.",'mlm');

      for($i = 0; $i<count($pair); $i++)
      {
        if($pair[$i]=="" || $amount[$i] == "")
          $error[] = __("Your bonus slab data is wrong.",'mlm');
      }

      if(empty($error))
      {
        $chk = '';
        update_option('letscms_mlm_bonus_settings', $_POST);
        $url = get_bloginfo('url')."/wp-admin/admin.php?page=binarymlm-admin-settings&tab=email";
        $msg = "<div class='notibar msgsuccess'><p>Your bonus settings have been successfully updated.</p></div>";
        
      }
    }// end outer if condition
    if($chk!='')
    {
      $mlm_settings = get_option('letscms_mlm_bonus_settings');
      
    ?>
  <div class='wrap1'>

  <?php if($error) {?>
    <div class="notibar msgerror">
      <p> <strong>Please Correct the following Error :</strong> <?php foreach($error as $er){ echo $er."</br>"; } ?></p>
    </div>
  <?php }
  
  $bonus_criteria = (isset($_POST['binarymlm_bonus_criteria']) ? $_POST['binarymlm_bonus_criteria'] : (isset($mlm_settings['binarymlm_bonus_criteria']) ? $mlm_settings['binarymlm_bonus_criteria'] : ''));
?>
<div class="container1" >
<div class="box1" ><h4 align="center">Binary MLM Bonus Settings</h4></div>
<div class="wrap2">
<form role="form" name="binarymlm_admin_bouns_settings" method="post" action="">
  <div class="col-md-12">
    <div class="col-md-6">
    <div class="form-group">
      <label for=""> <a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-bonus');"><?php _e('Bonus Criteria', 'mlm'); ?> <span style="color:red;">*</span>: </a></label>
      <select class="form-control" name="binarymlm_bonus_criteria" id="binarymlm_bonus_criteria">
                                <option value=""><?php _e('Select Bonus Criteria', 'mlm'); ?></option>
                                <option value="pair"  <?php echo  $bonus_criteria == 'pair' ? 'selected' : '' ?>>
                                    <?php _e('No. of Pairs', 'mlm'); ?></option>
                                <option value="personal"  <?php echo  $bonus_criteria == 'personal' ? 'selected' : '' ?>>
                                    <?php _e('No. of Personal Referrer', 'mlm'); ?></option>
      </select>
      <div class="toggle-visibility" id="binarymlm-admin-bonus"><?php _e('Please select bonus type.', 'mlm'); ?></div>
    </div>
  </div>

<div class="col-md-6">
    <div class="form-group">
      <label><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-bonus-slab');"><?php _e('Bonus Slab', 'mlm'); ?> <span style="color:red;">*</span>: </a> </label>
      <input type="button" value="Add Row" onclick="javascript:void(0);addRow('dataTable');"  class="btn btn-primary btn-default"/>
      <input type="button" value="Delete Row" onclick="javascript:void(0);deleteRow('dataTable');"  class="btn btn-primary btn-default"/>
      <div class="toggle-visibility" id="binarymlm-admin-bonus-slab"><?php _e('Please select bonus type.', 'mlm'); ?></div>
    
  </div>
</div>


  <table id="datatableheading"  border="0" align="center" style="margin-left:220px;width:25%">
      <tr>
          <td align="center" width="10%"><strong><?php _e('Select', 'mlm'); ?></strong></td>
          <td align="center" width="40%"> <strong><?php _e('No. of Pairs', 'mlm'); ?></strong>
          </td>
          <td align="center" width="40%"><strong><?php _e('Amount', 'mlm'); ?></strong></td>
      </tr>
  </table>
  <table id="dataTable"  border="0" align="center" style="margin-left:220px">

      <?php
      $i = 0;
      $pair = (isset($_POST['binarymlm_pair'])?$_POST['binarymlm_pair']:(isset($mlm_settings['binarymlm_pair'])?$mlm_settings['binarymlm_pair']:'0'));

      while ($i < count($pair)) {
          $mlm_settings['binarymlm_pair'][$i] = (isset($_POST['binarymlm_pair'][$i])?$_POST['binarymlm_pair'][$i]:(isset($mlm_settings['binarymlm_pair'][$i])?$mlm_settings['binarymlm_pair'][$i]:''));

          $mlm_settings['binarymlm_amount'][$i] = (isset($_POST['binarymlm_amount'][$i])?$_POST['binarymlm_amount'][$i]:(isset($mlm_settings['binarymlm_amount'][$i])?$mlm_settings['binarymlm_amount'][$i]:''));
          ?>
          <tr>
              <td align="center"><input type="checkbox" name="chk[]"/></td>
              <td align="center"><input type="text" name="binarymlm_pair[]" size="15" value="<?php echo  $mlm_settings['binarymlm_pair'][$i] ?>"/></td>
              <td align="center"><input type="text" name="binarymlm_amount[]" size="15" value="<?php echo  $mlm_settings['binarymlm_amount'][$i] ?>"/></td>
          </tr>     
          <?php
          $i++;
      }
      ?>
  </table>

                <div class="col-md-6">
  <div class="form-group">
    <button type="submit" name="binarymlm_bonus_settings" id="binarymlm_bonus_settings" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Submit &raquo;</button>
  </div>
  </div>

</form></div></div>
        <script language="JavaScript">
            populateArrays();
        </script></div>
        <?php
    } // end if statement
    else {
        echo $msg;
        echo "<script>window.location='$url'</script>";
    }
}//end mlmBonus function
}//end class
