<?php
defined( 'ABSPATH' ) || exit;
class Letscms_MLM_Update_Profile {
use letscms_mlmfunction;
  public function letscms_update_profile($id=''){
  	$this->letscms_check_user($id);
    global $wpdb, $current_user;
    $msg='';
    $error = array();
    if($id == '')
	{
			$userId = $current_user->ID;
			//$class = '';
	}
	else
	{
			$userId = $id;
			//$class = "class='button-primary'";
	}

  $user_info = get_userdata($userId);

  //most outer if condition
	if(isset($_POST['submit']))
	{
		$firstname = sanitize_text_field( $_POST['firstname'] );
		$lastname = sanitize_text_field( $_POST['lastname'] );
		$address1 = sanitize_text_field( $_POST['address1'] );
		$address2 = sanitize_text_field( $_POST['address2'] );
		$city = sanitize_text_field( $_POST['city'] );
		$state = sanitize_text_field( $_POST['state'] );
		$postalcode = sanitize_text_field( $_POST['postalcode'] );
		$telephone = sanitize_text_field( $_POST['telephone'] );
		$dob = sanitize_text_field( $_POST['dob'] );

    if ( $this->letscms_checkInputField($firstname) )
			$error[] = __("Please enter your first name.",'mlm');

		if ( $this->letscms_checkInputField($lastname) )
			$error[] = __("Please enter your last name.",'mlm');

		if ( $this->letscms_checkInputField($address1) )
			$error[] = __("Please enter your address.",'mlm');

		if ( $this->letscms_checkInputField($city) )
			$error[] = __("Please enter your city.",'mlm');

		if ( $this->letscms_checkInputField($state) )
			$error[] = __("Please enter your state.",'mlm');

		if ( $this->letscms_checkInputField($postalcode) )
			$error[] = __("Please enter your postal code.",'mlm');

		if ( $this->letscms_checkInputField($telephone) )
			$error[] = __("Please enter your contact number.",'mlm');

		if ( $this->letscms_checkInputField($dob) )
			$error[] = __("Please enter your date of birth.",'mlm');

		// inner if condition
		if(empty($error))
		{
				$user = array
				(
					'ID' => $userId,
					'first_name' => $firstname,
					'last_name' => $lastname,
				);

				// return the wp_users table inserted user's ID
				$user_id = wp_update_user($user);

				//get the selected country name from the country table
				$country = $_POST['country'];
				$sql = "SELECT name	FROM {$wpdb->prefix}binarymlm_country	WHERE id = '".$country."'";
        		$country1 = $wpdb->get_var($sql);

				//update the registration form data into user_meta table
        		update_user_meta( $user_id, 'user_address1', $address1 );
				update_user_meta( $user_id, 'user_address2', $address2 );
				update_user_meta( $user_id, 'user_city', $city );
				update_user_meta( $user_id, 'user_state', $state );
				update_user_meta( $user_id, 'user_country', $country1 );
				update_user_meta( $user_id, 'user_postalcode', $postalcode );
				update_user_meta( $user_id, 'user_telephone', $telephone );
				update_user_meta( $user_id, 'user_dob', $dob );
				$msg = "<div class='notibar msgsuccess'><p>Congratulations! Profile has been successfully updated.</p></div>";
		}//end inner if condition
    else{
      foreach($error as $err){
         echo $err."</br>";
       }
    }
	}//end most outer if condition
if($msg!='')
    echo $msg;
?>

	<form name="frm" role="form" method="post" action="" onSubmit="return updateFormValidation();">
		<div class="col-md-12">
	        <div class="form-group">
	          <label>First Name <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="firstname" id="letscms_firstname" value="<?= $user_info->first_name;?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'firstname');" >
	          <div id="check_firstname"></div>
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>Last Name <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="lastname" id="letscms_lastname" value="<?= $user_info->last_name;?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'lastname');">
	          <div id="check_lastname"></div>
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>Address Line 1 <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="address1" id="letscms_address1" value="<?= $user_info->user_address1;?>"  size="37" onBlur="return allowspace(this.value,'address1');">
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>Address Line 2 :</label>
	          <input type="text" class="form-control" name="address2" id="letscms_address2" value="<?= $user_info->user_address2;?>"  size="37" onBlur="return allowspace(this.value,'address2');">
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>City <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="city" id="letscms_city" value="<?= $user_info->user_city;?>" maxlength="30" size="37" onBlur="return allowspace(this.value,'city');">
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>State <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="state" id="letscms_state" value="<?= $user_info->user_state;?>" maxlength="30" size="37" onBlur="return allowspace(this.value,'state');">
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>Postal Code <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="postalcode" id="letscms_postalcode" value="<?= $user_info->user_postalcode;?>" maxlength="20" size="37" onBlur="return allowspace(this.value,'postalcode');">
	          <div id="allow_postalcode"></div>
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>Country <span style="color:red;">*</span> :</label>
	          <?php
		        $sql = "SELECT id, name	FROM {$wpdb->prefix}binarymlm_country ORDER BY name";
		        $results = $wpdb->get_results($sql);
						?>
						<select class="form-control" name="country" id="letscms_country" >
							<option value="">Select Country</option>
		        <?php
		        foreach($results as $row)
		        {
						if($user_info->user_country==$row->name)
						$selected = 'selected';
						else
						$selected = '';
		      	?>
		        <option value="<?= $row->id;?>" <?= $selected?>><?= $row->name;?></option>
						<?php } ?>
				</select>
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>Contact No. <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="telephone" id="letscms_telephone" value="<?= $user_info->user_telephone;?>" maxlength="20" size="37" onBlur="return numeric(this.value, 'telephone');" >
	          <div id="numeric_telephone"></div>
	        </div>
      	</div>

      	<div class="col-md-12">
	        <div class="form-group">
	          <label>Date of Birth <span style="color:red;">*</span> :</label>
	          <input type="text" class="form-control" name="dob" id="letscms_dob" value="<?= $user_info->user_dob;?>" maxlength="20" size="22" >
	        </div>
      	</div>

		<div class="col-md-12">
	        <div class="form-group">
	          <button type="submit" name="submit" id="submit" class="btn btn-primary btn-default">Update &raquo;</button>
	        </div>
      	</div>

	</form>

<?php
  }// end of function letscms_update_profile()
}// end of class
