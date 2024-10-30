<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Product_Settings{
  use letscms_mlmfunction;
  public function mlmProductPrice(){
    global $pagenow, $wpdb;
    $error = array();
    $chk = 'error';
	$this->letscms_check_first_user();

    $this->add_product_price();
    $this->update_product_price();
    
}

function add_product_price() {
    global $wpdb;
    $error = array();
    $chk = 'error';
    if (isset($_POST['add_price'])) {

        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];


        if ($product_name == '')
            $error[] = "Please Specify Product Name.";

        if ($product_price == '')
            $error[] = "Please Specify Product Price.";


        //if there is not any error found 
        if (empty($error)) {
            $chk = '';
            $wpdb->query("insert into {$wpdb->prefix}binarymlm_product_price (p_id,product_name,product_price) values ('','" . $product_name . "','" . $product_price . "')");
            $url = get_bloginfo('url')."/wp-admin/admin.php?page=binarymlm-admin-settings&tab=product_price&action=view";
            $msg = "<div class='notibar msgsuccess'><p>Your product price details have been successfully updated.</p></div>";
        }
    }
    if($chk!='')
   {
    ?>
    <?php if ($error) {?>
        <div class="notibar msgerror">
            <p> <strong><?php _e('Please Correct the following Error', 'mlm'); ?> :</strong> 
                <?php foreach($error as $err) {
                        echo $err."</br>";
                }
    ?></p></div>
    <?php } }else {  echo $msg;
                     echo "<script>window.location='$url'</script>";
     }?>

<div class="container1" >
<div class="box1" ><h4 align="center">Binary MLM Add Products</h4></div>
<div class="wrap2">
<form role="form" name="newproduct" action="" method="post">
  <div class="col-md-12">
    <div class="col-md-6">
    <div class="form-group">
      <label><strong><?php _e('Product Name', 'mlm'); ?></strong> <span style="color:red;">*</span></label>
       <input class="form-control" type="text" name="product_name" size="15" value="" class="text"/>
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group">
      <label><strong><?php _e('Product Price', 'mlm'); ?></strong> <span style="color:red;">*</span> </label>
      <input class="form-control" type="text" name="product_price" size="15" value="" class="text"/>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="form-group">
    <button type="submit" name="add_price" id="add_price" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Add Product &raquo;</button>
    </div>
 </div>
</div>
</form></div>
    <?php
}

function update_product_price() {
    global $wpdb;

    if (isset($_POST['update'])) {
        $wpdb->query("update {$wpdb->prefix}binarymlm_product_price set product_name='" . $_POST['product_name'] . "',product_price='" . $_POST['product_price'] . "' where p_id='" . $_POST['p_id'] . "'");
    }
    else if (isset($_POST['delete'])) {

        $wpdb->query("delete from {$wpdb->prefix}binarymlm_product_price where p_id='" . $_POST['p_id'] . "'");
    }
    $results = $wpdb->get_results("select * from {$wpdb->prefix}binarymlm_product_price where p_id!='1' order by p_id", ARRAY_A);

    $num = $wpdb->num_rows;
    if ($num > 0) {
        ?>
        <div class="container1" >
        <div class="box1" ><h4 align="center">Binary MLM View Products</h4></div>
        <div class="wrap2">
        <table class="table" id="datatableheading" style="width:80%;">
            <thead>
                <tr>
                    <td align="left" width="34%"> <strong><?php _e('Product Name', 'mlm'); ?></strong></td>

                    <td align="left" width="24%"> <strong><?php _e('Product Price', 'mlm'); ?></strong></td>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <?php
            foreach ($results as $detail) {

                ?>
                <tbody><tr>

<form name="" action="" method="post">
<td align="left" width="34%"> <input type="text" name="product_name" size="15" value="<?php echo $detail['product_name']; ?>"/> </td>
<td align="left" width="24%"> <input type="text" name="product_price" size="8" value="<?php echo $detail['product_price']; ?>"/></td>
<td align="left" width="42%"> 
<input type="hidden" class="f" name="p_id" value="<?php echo $detail['p_id']; ?>"/>
<input type="submit" class="f" name="update" value="update">
<input type="submit" class="f" name="delete" value="delete"></td>
</form></div>
</tr></tbody>
<?php }  ?>
        </table></div></div>

        <?php
    }
    else {
        echo "<strong>You have not Set the any product. Please add product first.</strong>";
    }
}
}// end of class
