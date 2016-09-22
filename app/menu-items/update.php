<?php
function kashaUssdMenuItemUpdate () {
	?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/ussd-manager/assets/style-admin.css" rel="stylesheet" />
<!-- IF THE USER CLICKED ON DELETE BUTTON THEN DELETE -->
<?php if (isset($_POST['delete'])) :	
 (new KashaUssdMenuItem)->delete($_GET['id']);?>
  <div class="updated"><p>item deleted</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_list') ?>">&laquo; Back to items list</a>
<?php exit; endif;?>

<!-- IF THE USER CLICKED ON UPDATE BUTTON THEN UPDATE -->
<?php if (isset($_POST['update'])) : 	
 (new KashaUssdMenuItem)->save($_POST); ?>
  <div class="updated"><p>item updated</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_list') ?>">&laquo; Back to items list</a>
<?php exit; endif;?>
<!-- GET items based on the provided id -->
<?php $item = (new KashaUssdMenuItem)->get()[0];?>


<div class="wrap">
<h2>item</h2>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<input type="hidden" name="id" value="<?php echo $item->id?>"/>
<table class='wp-list-table widefat fixed'>
<tr><th> Product name </th><td><?php echo $item->id.'.'. $item->name.'('.$item->price.get_woocommerce_currency_symbol() .')'; ?></td>
</tr>
<tr><th>Menu order</th><td><input type="text" name="quantity" value="<?php echo $item->menu_order;?>"/></td>
</tr>
</table>
<input type='submit' name="update" value='Save' class='button button-primary'> &nbsp;&nbsp;
<input type='submit' name="delete" value='Delete' class='button button-delete' onclick="return confirm('Are you sure you want to delete this item?')">
</form>


</div>
<?php
}
