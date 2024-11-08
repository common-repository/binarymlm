<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_My_Consultant {
  use letscms_mlmfunction;
  public function letscms_consultant_group(){
    $this->letscms_check_user();
    global $current_user, $wpdb;
    //get loged user's key
    	$key = $this->letscms_get_current_user_key();

    	//Total Users on My left leg
    	$leftLegUsers = $this->letscms_totalLeftLegUsers($key);

    	//paid users on my left leg
    	$rightLegUsers = $this->letscms_totalRightLegUsers($key);

    	$totalConsultant = $leftLegUsers + $rightLegUsers;

    	//show total users on left leg
    	$totalUsersSales = $this->letscms_totalSales($key);
      ?>

      <script type="text/javascript" src="//www.google.com/jsapi"></script>
      <script type="text/javascript">
        google.load('visualization', '1', {packages: ['table']});
      </script>
      <script type="text/javascript">
      var visualization;
      var data;
      var options = {
      width: 1000,
      chart: {
        title: 'MLM Network',
        subtitle: 'Right Gruop Details'
      },
      backgroundColor: {
        fill: '#FF0000',
        fillOpacity: 0.8
      },
      'showRowNumber': true
    }
      function drawVisualization() {
        // Create and populate the data table.
        var dataAsJson =
        {cols:[
      	{id:'A',label:'User Name',type:'string'},
      	{id:'B',label:'User Key',type:'string'},
      	{id:'C',label:'Sponsor',type:'string'},
      	{id:'D',label:'Placement',type:'string'},
      	{id:'E',label:'Status',type:'string'}],
        rows:[
         <?php foreach( $totalUsersSales as $details)
         		{
         			foreach( $details as $row) :  ?>
      		{c:[{v:'<?=$row['username']?>'},{v:'<?=$row['user_key']?>'},{v:'<?=$row['sponsor_key']?>'},{v:'<?=$row['leg']?>'},{v:'<?=$row['payment_status']?>'}]},
      	<?php endforeach ;
      		}
      	?>
        ]};
        data = new google.visualization.DataTable(dataAsJson);
        // Set paging configuration options
        // Note: these options are changed by the UI controls in the example.
        options['page'] = 'enable';
        options['pageSize'] = 10;
        options['pagingSymbols'] = {prev: 'prev', next: 'next'};
        options['pagingButtonsConfiguration'] = 'auto';
        //options['allowHtml'] = true;
        //data.sort({column:1, desc: false});
        // Create and draw the visualization.
        visualization = new google.visualization.Table(document.getElementById('table'));
        draw();
      }
      function draw() {
        visualization.draw(data, options);
      }
      google.setOnLoadCallback(drawVisualization);
      // sets the number of pages according to the user selection.
      function setNumberOfPages(value) {
        if (value) {
      	options['pageSize'] = parseInt(value, 10);
      	options['page'] = 'enable';
        } else {
      	options['pageSize'] = null;
      	options['page'] = null;
        }
        draw();
      }
      // Sets custom paging symbols "Prev"/"Next"
      function setCustomPagingButtons(toSet) {
        options['pagingSymbols'] = toSet ? {next: 'next', prev: 'prev'} : null;
        draw();
      }
      function setPagingButtonsConfiguration(value) {
        options['pagingButtonsConfiguration'] = value;
        draw();
      }
      </script>
      <!--va-matter-->
          <div class="va-matter">
          	<!--va-matterbox-->
          	<div class="va-matterbox">
              	<!--va-headname-->
              	<div class="va-headname"><center><h6>My Consultants Details</h6></center></div>
                  <!--/va-headname-->
      			<div class="va-admin-leg-details">
                  	<!--va-admin-mid-->
      				<div class="paging">
      				  <form action="">
      					<div class="left-side">
      						Display Number of Rows : &nbsp;
      					</div>
      					<div class="right-side">
      						<select style="font-size: 12px" onchange="setNumberOfPages(this.value)">
      						  <option value="5">5</option>
      						  <option selected="selected" value="10">10</option>
      						  <option value="20">20</option>
      						  <option  value="50">50</option>
      						  <option value="100">100</option>
      						  <option value="500">500</option>
      						   <option value="">All</option>
      						</select>
      					</div>
      					</form>
      					<div class="right-members">
      					Consultants: &nbsp; Left: <strong><?= $leftLegUsers; ?></strong>&nbsp;Right: <strong><?= $rightLegUsers; ?></strong>
      					&nbsp; Total: <strong><?= $totalConsultant;?></strong>
      					</div>
      					<div class="va-clear"></div>
      				  </div>
      				<div id="table"></div>
      				<div class="va-clear"></div>
      			</div>
      		</div>
      	</div>
        <?php
  } //end of function
}//end of class
