<?php
function kasha_ussd_list () {
?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/ussd-manager/assets/style-admin.css" rel="stylesheet" />
<div class="wrap">
<h2>Addresses</h2>
<a href="<?php echo admin_url('admin.php?page=kasha_ussd_address_create'); ?>" class="page-title-action">Add New</a>
<?php $addresses = (new KashaUssdAddress)->get();?>
<table class='wp-list-table widefat fixed striped'>
<tr>
	<th>first_name</th>
	<th>last_name</th>
	<th>company</th>
	<th>email</th>
	<th>phone</th>
	<th>address_1</th>
	<th>address_2</th>
	<th>city</th>
	<th>state</th>
	<th>postcode</th>
	<th>country</th>
</tr>
<?php foreach ($addresses as $address ): ?>
<tr>
	<td><?php echo $address->first_name;?></td>
	<td><?php echo $address->last_name;?></td>
	<td><?php echo $address->company;?></td>
	<td><?php echo $address->email;?></td>
	<td><?php echo $address->phone;?></td>
	<td><?php echo $address->address_1;?></td>
	<td><?php echo $address->address_2;?></td>
	<td><?php echo $address->city;?></td>
	<td><?php echo $address->state;?></td>
	<td><?php echo $address->postcode;?></td>
	<td><?php echo $address->country;?></th>
	<td><a href='<?php echo admin_url('admin.php?page=kasha_ussd_address_update&id='.$address->id) ?>' class='button button-primary button-large'>Update</a></td>
</td>
<?php endforeach;?>
</table>

</div>
<?php } ?>