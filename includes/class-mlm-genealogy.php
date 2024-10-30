<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Genealogy {
use letscms_mlmfunction;
	//$clients array contain the nodes
	public $clients=array();

	//$add_page_id variable take the wordpress pageID for the network registration
	public $add_page_id;

	//$view_page_id take the wordpress pageID where the netwok to open
	public $view_page_id;

	//$counter varibale take the how many level you want to shows the network
	public $counter = 4;

	//addLeftLeg() function build the Left leg registration node of the network
	function addLeftLeg($key, $add_page_id)
	{
		$username = $this->letscms_getUsername($key);
		$str = "[{v:'".$key."ADD',f:'<a href=\"?page_id=".$add_page_id."&k=".$key."&l=0\">ADD</a><br><span class=\"leg\">Left leg</span>'},'".$username."',''],";
		return $str;
	}

	//addRightLeg() function build the Right leg registration node of the network
	function addRightLeg($key, $add_page_id)
	{
		$username = $this->letscms_getUsername($key);
		$str = "[{v:'".$key."ADD2',f:'<a href=\"?page_id=".$add_page_id."&k=".$key."&l=1\">ADD</a><br><span class=\"leg\">Right leg</span>'},'".$username."',''],";
		return $str;
	}

//buildRootNetwork() function take the key and build the root node of the network
function buildRootNetwork($key)
{
	$level = array();
	$username = $this->letscms_getUsername($key);
	$myclients[]="[{v:'".$username."', f:'".$username."<br><span class=\"owner\">Owner</span>'}, '', 'The owner'],";
	$this->clients[] = $myclients;
	$level[] = $key;
	return $level;
}

//buildLevelByLevelNetwork() function build the 1st and more level network
	function buildLevelByLevelNetwork($key, $add_page_id, $view_page_id, $counter, $level)
	{
		global $wpdb;
		$level1 = array();

			for($i=0; $i<$counter; $i++)
			{
				if(!isset($level[$i])){ $level[$i]=null; }
			$myclients = array();
			if($level[$i]!='add' && $level[$i]!='')
			{
				$sql = "SELECT username, payment_status, user_key, leg FROM {$wpdb->prefix}binarymlm_users WHERE parent_key = '".$level[$i]."' AND banned='0' ORDER BY leg DESC";
				$results = $wpdb->get_results($sql);
				$num = $wpdb->num_rows;
        // no child case
				if(!$num)
				{
					$myclients[]= $this->addLeftLeg($level[$i], $add_page_id);
					$myclients[]= $this->addRightLeg($level[$i], $add_page_id);
					$level1[] = 'add';
					$level1[] = 'add';
				}
				//if child exist
				else if($num > 0)
				{
				$username = $this->letscms_getUsername($level[$i]);

          foreach($results as $key => $row)
					{
            $user_key = $row->user_key;
            $payment_status = $row->payment_status;
						//check user paid or not
						$payment = $this->checkPaymentStatus($user_key, $payment_status,$view_page_id);
            //if only one child exist
						if($num == 1)
						{
							//if right leg child exist
							if($row->leg==1)
							{
								$myclients[] = $this->addLeftLeg($level[$i], $add_page_id);
								$myclients[] = "[{v:'".$row->username."',f:'".$row->username.$payment."'}, '".$username."', ''],";
								//$myclients[] = "[$row->username, '".$username."', ''],";
								//$myclients[] = "[{'".$row->username."'}, '".$username."', ''],";
								$level1[] = 'add';
								$level1[] = $row->user_key;
							}
							else  // if left leg child exist
							{
								$myclients[] = "[{v:'".$row->username."',f:'".$row->username.$payment."'}, '".$username."', ''],";
								$myclients[] = $this->addRightLeg($level[$i], $add_page_id);
								$level1[] = $row->user_key;
								$level1[] = 'add';
							}
						}
						else  //both child exist left and right leg
						{
							$myclients[] = "[{v:'".$row->username."',f:'".$row->username.$payment."'}, '".$username."', ''],";
							$level1[] = $row->user_key;
						}
					} //end foreach loop
				}
				$this->clients[] = $myclients;
			} // end most outer if statement
		} //end for loop
		return $level1;
	}

	//checkPaymentStatus() function check the node user is paid or not
		function checkPaymentStatus($key, $payment, $view_page_id)
		{
			$paymentStr='<br><a href="?page_id='.$view_page_id.'&k='.$key.'">View</a>';

			if($payment==1)
			{
				$paymentStr.='<br><span class=\"paid\">PAID</span>';
			}
			return $paymentStr;
		}
		function network()
		{
			global $wpdb;
			global $current_user;
			$username2 = $current_user->user_login;
			$query="SELECT * FROM {$wpdb->prefix}binarymlm_users WHERE username = '".$username2."'";
			$res12 = $wpdb->get_row($query);
			if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['search']) && $_POST['search'])
			{
			$username = $_POST['username'];
			$query2="SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE username = '".$username."'";
			$result2 = $wpdb->get_results($query2);
			$num = $wpdb->num_rows;
			if($num)
			{
				$result = $wpdb->get_row($query2);
				$query3="SELECT pkey FROM {$wpdb->prefix}binarymlm_leftleg WHERE ukey = '".$result->user_key."' AND pkey = '".$res12->user_key."'";
				$wpdb->get_row($query3);
				$leftnum = $wpdb->num_rows;
				if($leftnum){
					$key = $result->user_key;
				}
				else{
					$query4="SELECT pkey FROM {$wpdb->prefix}binarymlm_rightleg WHERE ukey = '".$result->user_key."' AND pkey = '".$res12->user_key."'";
					$wpdb->get_row($query4);
					$rightnum = $wpdb->num_rows;
					if($rightnum){
					$key = $result->user_key;
					}
					else{
					?>
					<p style="margin:0 auto;padding:0px;font-family:Arial, Helvetica, sans-serif;font-size:20px; text-align:center; font-weight:bold; padding:25px 0px 15px 0px;color:grey;">You can't authorized to access searched user's genealogy.</p>
					<div class="button-wrap" style="height:14px; margin-left:20px;">
					<div class="red button"><a href="javascript:history.back(1)">Back</a></div></div>
					<?php
					exit;
				}
			}
		}// end of check $num condition
			else{

			?>
			<p style="margin:0 auto;padding:0px;font-family:Arial, Helvetica, sans-serif;font-size:20px; text-align:center; font-weight:bold; padding:25px 0px 15px 0px;color:grey;">You have searched a wrong username.</p>
			<div class="button-wrap" style="height:14px; margin-left:20px;">
			<div class="red button"><a href="javascript:history.back(1)">Back</a></div></div>
			<?php
			exit;
			}
			}
			else
			{
				if(!empty($_GET['k']) && $_GET['k']!='')
			{
			$key = $_GET['k'];
			}
			else
			{
			$key = $this->letscms_get_current_user_key();
			}
		}

			if(!$this->letscms_checkKey($key))
			{
				$this->letscms_check_user();
				
			}
			/*********************************************** Root node ******************************************/
			$level = $this->buildRootNetwork($key);

			/*********************************************** First level ******************************************/
			$level = $this->buildLevelByLevelNetwork($key, $this->add_page_id, $this->view_page_id, 1, $level);

		/*********************************************** 2 and more level's ******************************************/
			if($this->counter>=2)
			{
				$j = 1;
				for($i = 2; $i<= $this->counter; $i++)
				{

					$j = $j * 2;
					$level = $this->buildLevelByLevelNetwork($key, $this->add_page_id, $this->view_page_id, $j, $level);
				}
			}

			return $this->clients;

		}//end of function network()

public function letscms_genealogy_network(){
    global $wpdb;
    global $current_user;

    $username1 = $current_user->user_login;

		$this->add_page_id = $this->letscms_get_post_id(LETSCMS_MLM_REGISTRATION_TITLE);
		$this->view_page_id = $this->letscms_get_post_id(LETSCMS_MLM_GENEALOGY_TITLE);

  	$var = $this->network();
    $res = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE username = '".$username1."'");

?>

<style type="text/css">
		span.owner
		{
			//color:#339966;
			color:#ffffff !important;
			font-style:italic;
		}
		span.paid
		{
			color: #669966!important;
			font-style:normal;
		}
		span.leg
		{
			color:red;
			font-style:italic;
		}

		.google-visualization-orgchart-nodesel,.google-visualization-orgchart-node {
border: 2px solid #B3B3B3 !important;
background:#051130 !important;
background-color: #fff7ae;
color:#ffffff !important;
background: -webkit-gradient(linear, left top, left bottom, from(#fff7ae), to(#eee79e));
}
	.google-visualization-orgchart-nodesel a,.google-visualization-orgchart-node a
	{
	color:#ffffff !important;
	}
	#chart_div table
	{
	border-collapse:separate !important;
	}
	.google-visualization-orgchart-lineleft {
	border-left: 1px solid #B3B3B3 !important;
	}
	.google-visualization-orgchart-lineright
	{
	border-right: 1px solid #B3B3B3 !important;
	}
	.google-visualization-orgchart-linebottom {
	border-bottom: 1px solid #B3B3B3 !important;
	}
	</style>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
      google.load('visualization', '1', {packages:['orgchart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');
        data.addRows([<?php for($i=0;$i<count($var);$i++){for($j=0;$j<2;$j++){if(!empty($var[$i][$j]))echo $var[$i][$j];}}?> ['', null,'']]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
			}
</script>
<script type="text/javascript" language="javascript">
	function searchUser()
	{
		var user = document.getElementById("username").value;
		if(user=="")
		{
			alert("Please enter username then searched.");
			document.getElementById("username").focus();
			return false;
		}
	}
</script>
    <div style="margin-left:20px;"><table border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td align="center">
		<form action="<?php bloginfo('url'); ?>/?page_id=<?=$this->view_page_id?>&k=<?php echo $res;?>" method="post">
		<input type="submit" value="YOU" style="margin-bottom:10px;">
		</form>
		</td>
		<td align="center">
		<form name="usersearch" id="usersearch" action="" method="post" onSubmit="return searchUser();">
		<input type="text" name="username" id="username"> <input type="submit" name="search" value="Search" style="margin-bottom:10px;">
		</form>
		</td>
	  </tr>
		</table>
		</div>
		<div style="margin:0 auto;padding:0px;clear:both; width:100%!important;" align="center">
    <div id='chart_div'></div></div>
    <?php
  }

}//end of Class
