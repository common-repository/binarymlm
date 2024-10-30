<?php
defined( 'ABSPATH' ) || exit;
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Letscms_MLM_My_Left_Group extends WP_List_Table {
  use letscms_mlmfunction;

    function get_columns() {
        $columns = array(
            'epin' => __('Pin No.', 'mlm'),
            'epinprice' => __('Pin Price', 'mlm'),
            'username' => __('Username', 'mlm'),
            'firstname' => __('First Name', 'mlm'),
            'lastname' => __('Last Name', 'mlm'),
            'type' => __('Type', 'mlm'),
            'genarated_on' => __('Generated On', 'mlm'),
            'date_used' => __('Used on', 'mlm'),
        );
        return $columns;
    }
    
    function prepare_items() {
      global $wpdb;
        $per_page = 30;
  $columns = $this->get_columns();
  $hidden = array();
  $sortable = array();
  $this->_column_headers = array($columns, $hidden, $sortable);
  


  $data = $listArr;
  $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));

    }
  function myTotalLeftLegUsers($pkey)
  {
    global $wpdb;
    $sql = "SELECT username, payment_status, user_key, sponsor_key FROM {$wpdb->prefix}binarymlm_users WHERE user_key IN
                  (
                    SELECT ukey FROM {$wpdb->prefix}binarymlm_leftleg WHERE pkey = '".$pkey."' ORDER BY id DESC
                  )
                  ORDER BY id DESC";

   $results = $wpdb->get_results($sql);
   $i = 0;
    if($wpdb->num_rows > 0)
    {
      foreach($results as $data)
      {
        $users[$i]['username'] = $data->username;
        $users[$i]['user_key'] = $data->user_key;
        $users[$i]['sponsor_key'] = $this->letscms_getSponsorName($data->sponsor_key);
        $users[$i]['payment_status'] = $this->letscms_activeNotActive($data->payment_status);
        $i++;
      }
    }
    else
    {
      $users[$i]['username'] = _e("No Member Found",'mlm');
      $users[$i]['payment_status'] = '';
    }
    return $users;
  }

  public function letscms_left_group($id=''){
    $this->letscms_check_user($id);
    global $wpdb;
      //get loged user's key
      if($id == '')
          $key = $this->letscms_get_current_user_key();
      else
        $key = $wpdb->get_var("SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE user_id = $id");

      //Total Users on My left leg
      $leftLegUsers = $this->letscms_totalLeftLegUsers($key);

      //paid users on my left leg
      $leftLegActiveUsers = $this->letscms_activeUsersOnLeftLeg($key);

      //show total users on left leg
      $totalLeftLegUsers = $this->myTotalLeftLegUsers($key);

      $this->prepare_items();

?>
<script type="text/javascript" src="//www.google.com/jsapi"></script>
<script type="text/javascript">google.load('visualization', '1', {packages: ['table']}); </script>
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
//var options = {'width': 1000,'showRowNumber': true};
function drawVisualization() {
  // Create and populate the data table.
  var dataAsJson =
  {cols:[
  {id:'A',label:'<?php _e("User Name",'mlm'); ?>',type:'string'},
  {id:'B',label:'<?php _e("User Key",'mlm'); ?>',type:'string'},
  {id:'C',label:'<?php _e("Sponsor",'mlm'); ?>',type:'string'},
  {id:'D',label:'<?php _e("Status",'mlm'); ?>',type:'string'}],
  rows:[
   <?php foreach( $totalLeftLegUsers as $row) :  ?>
    {c:[{v:'<?=$row['username']?>'},{v:'<?=$row['user_key']?>'},{v:'<?=$row['sponsor_key']?>'},{v:'<?=$row['payment_status']?>'}]},
  <?php endforeach ;?>
  ]};
  data = new google.visualization.DataTable(dataAsJson);
  // Set paging configuration options
  // Note: these options are changed by the UI controls in the example.
  options['page'] = 'enable';
  options['pageSize'] = 10;
  options['pagingSymbols'] = {prev: 'PREV', next: 'NEXT'};
  options['pagingButtonsConfiguration'] = 'auto';
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
  options['pagingSymbols'] = toSet ? {next: '<?php _e("next",'mlm'); ?>', prev: '<?php _e("prev",'mlm'); ?>'} : null;
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
      <div class="va-headname"><center><h6><?php _e("My Left Group Details",'mlm'); ?></h6></center></div>
          <!--/va-headname-->
      <div class="va-admin-leg-details">
          <!--va-admin-mid-->
        <div class="paging">
          <form action="">
          <div class="left-side"><?php _e("Display Number of Rows : &nbsp",'mlm');?></div>
          <div class="right-side">
          <select style="font-size: 12px" onchange="setNumberOfPages(this.value)">
          <option value="5">5</option>
          <option selected="selected" value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
          <option value="500">500</option>
          <option value="">All</option>
          </select>
          </div>
          </form>
          <div class="right-members">
          <?php _e("Total Users:",'mlm'); ?> <strong><?= $leftLegUsers; ?></strong>&nbsp;<?php _e("Active Users:",'mlm'); ?><strong><?= $leftLegActiveUsers; ?></strong>
          </div>
          <div class="va-clear"></div>
          </div>
        <div id="table"></div>
        <div class="va-clear"></div>
      </div>
    </div>
  </div>
<?php
  }//end of function letscms_left_group()
}//end of class
