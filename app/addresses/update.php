<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/ussd-manager/assets/style-admin.css" rel="stylesheet" />
<?php
function kashaUssdAddressUpdate () {
	
?>

<!-- IF THE USER CLICKED ON DELETE BUTTON THEN DELETE -->
<?php if ($_POST['delete']) :	(new KashaUssdAddress)->delete($_GET['id']);?>
  <div class="updated"><p>Address deleted</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_list') ?>">&laquo; Back to Addresss list</a>
<?php exit; endif;?>

<!-- IF THE USER CLICKED ON UPDATE BUTTON THEN UPDATE -->
<?php if ($_POST['update']) : 	(new KashaUssdAddress)->save($_POST); ?>
  <div class="updated"><p>Address updated</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_list') ?>">&laquo; Back to Addresss list</a>
<?php endif;?>
<!-- GET Addresss based on the provided id -->
<?php $address = (new KashaUssdAddress)->find($_GET['id']);?>


<div class="wrap">
<h2>Address</h2>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class='wp-list-table widefat fixed'>
<input type="hidden" name="id" value="<?php echo $address->id?>"/>
<tr><th>First name</th><td><input type="text" name="first_name" value="<?php echo $address->first_name?>"/></td></tr>
<tr><th>Last name</th><td><input type="text" name="last_name" value="<?php echo $address->last_name?>"/></td></tr>
<tr><th>Company</th><td><input type="text" name="company" value="<?php echo $address->company?>"/></td></tr>
<tr><th>Email</th><td><input type="text" name="email" value="<?php echo $address->email?>"/></td></tr>
<tr><th>Phone</th><td><input type="text" name="phone" value="<?php echo $address->phone?>"/></td></tr>
<tr><th>Address_1</th><td><input type="text" name="address_1" value="<?php echo $address->address_1?>"/></td></tr>
<tr><th>Address_2</th><td><input type="text" name="address_2" value="<?php echo $address->address_2?>"/></td></tr>
<tr><th>City</th><td><input type="text" name="city" value="<?php echo $address->city?>"/></td></tr>
<tr><th>State</th><td><input type="text" name="state" value="<?php echo $address->state?>"/></td></tr>
<tr><th>Country</th><td><input type="text" name="country" value="<?php echo $address->country?>"/></td></tr>

</table>
<input type='submit' name="update" value='Save' class='button button-primary'> &nbsp;&nbsp;
<input type='submit' name="delete" value='Delete' class='button button-delete' onclick="return confirm('Are you sure you want to delete this address?')">
</form>


</div>
<?php
}