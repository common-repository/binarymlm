<?php
class Letscms_MLM_Cron_scheduling{
  use letscms_mlmfunction;
  /*************************************Cron Settings*****************************************/
  public function mlmCron(){
    global $wpdb;
    $error = array();
    $chk = 'error';
    //most outer if condition
    if(isset($_POST['binarymlm_cron_settings']))
    {
     $days = sanitize_text_field( $_POST['binarymlm_days'] );
     $hours = sanitize_text_field( $_POST['binarymlm_hours'] );
     $minutes = sanitize_text_field( $_POST['binarymlm_minutes'] );
     
     if ( $this->letscms_checkInputField($days) )
        $error[] = __("Your days value is wrong.",'mlm');

      if ( $this->letscms_checkInputField($hours) )
        $error[] = __("Your hours value is wrong.",'mlm');

      if ( $this->letscms_checkInputField($minutes) )
        $error[] = __("Your minutes value is wrong.",'mlm');

      //if no error occured
      if(empty($error))
      {
        $chk = '';
        update_option('letscms_mlm_cron_settings', $_POST);
        $url = get_bloginfo('url')."/wp-admin/admin.php?page=binarymlm-admin-settings&tab=cron scheduling";
        echo "<script>window.location='$url'</script>";
        $msg = "<span style='color:green;'>Your cron settings has been successfully updated.</span>";
      }
    }// end outer if condition
   if($chk!='')
   {
     $mlm_settings = get_option('letscms_mlm_cron_settings');
    ?>
      <div class='wrap1'>
      <h2>Cron Scheduling Setting </h2>
      
      <?php if($error) {?>
       <div class="notibar msgerror">
     <a class="close"></a>
     <p> <strong><?php _e('Please Correct the following Error :','mlm'); ?></strong> <?php foreach($error as $err){ echo $err."</br>"; } ?></p>
     </div>
  <?php }
     if(empty($mlm_settings))
     {
        ?>
   <form name="admin_cron_settings" method="post" action="">
   <table border="0" cellpadding="0" cellspacing="0" width="100%" border="2" class="form-table">
<tr><th>Days<span style="color:red;">*</span></th>
    <td><select name="days" id="days">
      <option value="">Select Days</option>
        <option value="*">*</option>
        <?php
        $i=1;
        for($i=1;$i<=31;$i++){
          ?>
          <option value="<?php echo $i; ?>" <?php echo $mlm_settings['binarymlm_days']=='<?php echo $i; ?>' ? 'selected':''?>><?php echo $i; ?></option><?php } ?>
        </select></td>
</tr>
<tr><th>Hours<span style="color:red;">*</span></th>
    <td><select name="hours" id="hours">
      <option value="">Select Hours</option>
        <option value="*">*</option>
        <?php
        $i=1;
        for($i=1;$i<=24;$i++){
          ?>
          <option value="<?php echo $i; ?>" <?php echo $mlm_settings['binarymlm_hours']=='<?php echo $i; ?>' ? 'selected':''?>><?php echo $i; ?></option><?php } ?>
        </select></td>
</tr>
<tr><th>Minutes<span style="color:red;">*</span></th>
    <td><select name="minutes" id="minutes">
      <option value="">Select Minutes</option>
          <option value="*">*</option>
          <?php
        $i=1;
        for($i=1;$i<=60;$i++){
          ?>
          <option value="<?php echo $i; ?>" <?php echo $mlm_settings['binarymlm_minutes']=='<?php echo $i; ?>' ? 'selected':''?>><?php echo $i; ?></option><?php } ?>
        </select></td>
</tr>

   </table><p class="submit">
   <input type="submit" name="mlm_cron_settings" id="mlm_cron_settings" value="<?php _e('Update Options', 'mlm')?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
   </p>
   </form>
   </div>
    <?php
  }
     else if(!empty($mlm_settings))
     {
     ?>
       <form name="admin_cron_settings" method="post" action="">
   <table border="0" cellpadding="0" cellspacing="0" width="100%" border="2" class="form-table">
      <tr><th>Days<span style="color:red;">*</span></th>
    <td><select name="days" id="days">
      <option value="">Select Days</option>
        <option value="*">*</option>
        <?php
        $i=1;
        for($i=1;$i<=31;$i++){
          ?>
          <option value="<?php echo $i; ?>" <?php echo $mlm_settings['binarymlm_days']==$i ? 'selected':''?>><?php echo $i; ?></option><?php } ?>
        </select></td>
</tr>
<tr><th>Hours<span style="color:red;">*</span></th>
    <td><select name="hours" id="hours">
      <option value="">Select Hours</option>
        <option value="*">*</option>
        <?php
        $i=1;
        for($i=1;$i<=24;$i++){
          ?>
          <option value="<?php echo $i; ?>" <?php echo $mlm_settings['binarymlm_hours']==$i ? 'selected':''?>><?php echo $i; ?></option><?php } ?>
        </select></td>
</tr>
<tr><th>Minutes<span style="color:red;">*</span></th>
    <td><select name="minutes" id="minutes">
      <option value="">Select Minutes</option>
          <option value="*">*</option>
          <?php
        $i=1;
        for($i=1;$i<=60;$i++){
          ?>
          <option value="<?php echo $i; ?>" <?php echo $mlm_settings['binarymlm_minutes']==$i ? 'selected':''?>><?php echo $i; ?></option><?php } ?>
        </select></td>
</tr>

     </table>
     <p class="submit">
   <input type="submit" name="binarymlm_cron_settings" id="binarymlm_cron_settings" value="<?php _e('Update Options', 'mlm')?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
   </p>
   </form>
   </div>
     <?php
     }
  } // end if chk statement
  else
     echo $msg;
   }
}
 ?>
