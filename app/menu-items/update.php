<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/ussd-manager/assets/style-admin.css" rel="stylesheet" />
<?php
function kashaUssdMenuItemUpdate () {
	
?>

<!-- IF THE USER CLICKED ON DELETE BUTTON THEN DELETE -->
<?php if ($_POST['delete']) :	(new KashaUssdMenuItem)->delete($_GET['id']);?>
  <div class="updated"><p>item deleted</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_list') ?>">&laquo; Back to items list</a>
<?php exit; endif;?>

<!-- IF THE USER CLICKED ON UPDATE BUTTON THEN UPDATE -->
<?php if ($_POST['update']) : 	(new KashaUssdMenuItem)->save($_POST); ?>
  <div class="updated"><p>item updated</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_list') ?>">&laquo; Back to items list</a>
<?php endif;?>
<!-- GET items based on the provided id -->
<?php $item = (new KashaUssdMenuItem)->find($_GET['id']);?>


<div class="wrap">
<h2>item</h2>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<input type="hidden" name="id" value="<?php echo $item->id?>"/>
<table class='wp-list-table widefat fixed'>
<tr><th>Woocommerce Product id</th><td><input type="text" name="woocommerce_item_id" value="<?php echo $item->woocommerce_item_id;?>"/></td></tr>
<tr><th>Name</th><td><input type="text" name="name" value="<?php echo $item->name;?>"/></td></tr>
<tr><th>Price</th><td><input type="text" name="price" value="<?php echo $item->price;?>"/></td></tr>
<tr><th>Quantity</th><td><input type="text" name="quantity" value="<?php echo $item->quantity;?>"/></td>
<tr><th>Menu order</th><td><input type="text" name="quantity" value="<?php echo $item->order;?>"/></td>

</tr>

</table>
<input type='submit' name="update" value='Save' class='button button-primary'> &nbsp;&nbsp;
<input type='submit' name="delete" value='Delete' class='button button-delete' onclick="return confirm('Are you sure you want to delete this item?')">
</form>


</div>
<?php
}