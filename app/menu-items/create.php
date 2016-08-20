<?php
function kashaUssdMenuItemCreate () {
	
?>
<!-- IF THE USER CLICKED ON UPDATE BUTTON THEN UPDATE -->
<?php if ($_POST['save'] && (new KashaUssdMenuItem)->save($_POST) > 0) : 	; ?>
  <div class="updated"><p>Ussd menu item added</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_list') ?>">&laquo; Back to Ussd menu items list</a>
<?php exit; endif;?>


<div class="wrap">
<h2>Ussd menu item</h2>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class='wp-list-table widefat fixed'>
<tr><th>Woocommerce Product id</th><td><input type="text" name="woocommerce_item_id" value=""/></td></tr>
<tr><th>Name</th><td><input type="text" name="name" value=""/></td></tr>
<tr><th>Price</th><td><input type="text" name="price" value=""/></td></tr>
<tr><th>Quantity</th><td><input type="text" name="quantity" value=""/></td></tr>
<tr><th>Menue order</th><td><input type="text" name="order" value=""/></td></tr>

</table>
<input type='submit' name="save" value='Add' class='button button-primary'> &nbsp;&nbsp;
</form>


</div>
<?php
}