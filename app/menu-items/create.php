<?php
function kashaUssdMenuItemCreate () {
	$wooCommerceItems = (new KashaUssdMenuItem)->wooCommerceItems();
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
<tr><th>Product</th>
<td>
  <select name="woocommerce_item_id" class="select2-drop-mask">
  	<?php foreach($wooCommerceItems as $item): ?>
  		<option value="<?php echo $item->id; ?>"> <?php echo $item->id.'.'. $item->name.'('.$item->price.get_woocommerce_currency_symbol() .')'; ?> </option>
  	<?php endforeach;?>
  </select>
</td></tr>
<tr><th>Menue order</th><td><input type="text" name="menu_order" value=""/></td></tr>

</table>
<input type='submit' name="save" value='Add' class='button button-primary'> &nbsp;&nbsp;
</form>


</div>
<?php
}