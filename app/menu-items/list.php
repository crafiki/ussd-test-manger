<?php
function kashaUssdMenuItems () {
	var_dump(get_woocommerce_product_list());exit;
?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/ussd-manager/assets/style-admin.css" rel="stylesheet" />
<div class="wrap">
<h2>Addresses</h2>
<a href="<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_create'); ?>" class="page-title-action">Add Menu item</a>
<?php $items = (new KashaUssdMenuItem)->get();?>

<table class='wp-list-table widefat fixed striped'>
<tr>
	<th>woocommerce_item_id</th>
	<th>name</th>
	<th>price</th>
	<th>quantity</th>
	<th></th>
</tr>
<?php foreach ($items as $item ): ?>
<tr>
	<td><?php echo $item->woocommerce_item_id;?></td>
	<td><?php echo $item->name;?></td>
	<td><?php echo $item->price;?></td>
	<td><?php echo $item->quantity;?></td>
	<td><a href='<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_update&id='.$item->id) ?>' class='button button-primary button-large'>Update</a></td>
</td>
<?php endforeach;?>
</table>

</div>
<?php } ?>