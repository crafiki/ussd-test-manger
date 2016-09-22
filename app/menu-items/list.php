<?php
function kashaUssdMenuItems () {
?>

<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/ussd-manager/assets/style-admin.css" rel="stylesheet" />
<div class="wrap">
<h2>Menu items</h2>
<a href="<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_create'); ?>" class="page-title-action">Add Menu item</a>
<?php $items = (new KashaUssdMenuItem)->get();?>

<table class='wp-list-table widefat fixed striped'>
<tr>
	<th>Order</th>
	<th>name</th>
	<th>price</th>
	<th></th>
</tr>
<?php foreach ($items as $item ): ?>
<tr>
	<td><?php echo $item->menu_order;?></td>
	<td><?php echo $item->name;?></td>
	<td><?php echo $item->price;?></td>
	<td><a href='<?php echo admin_url('admin.php?page=kasha_ussd_menu_item_update&id='.$item->id) ?>' class='button button-primary button-large'>Update</a></td>
</td>
<?php endforeach;?>
</table>

</div>
<?php } ?>
