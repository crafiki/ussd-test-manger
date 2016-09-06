<?php
function kashaUssdAddressCreate () {
	
?>
<!-- IF THE USER CLICKED ON UPDATE BUTTON THEN UPDATE -->
<?php if ($_POST['save'] && (new KashaUssdAddress)->save($_POST) > 0) : 	; ?>
  <div class="updated"><p>Address added</p></div>
  <a href="<?php echo admin_url('admin.php?page=kasha_ussd_list') ?>">&laquo; Back to Addresss list</a>
<?php endif;?>


<div class="wrap">
<h2>Address</h2>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class='wp-list-table widefat fixed'>
<tr><th>First name</th><td><input type="text" name="first_name" value=""/></td></tr>
<tr><th>Last name</th><td><input type="text" name="last_name" value=""/></td></tr>
<tr><th>Company</th><td><input type="text" name="company" value=""/></td></tr>
<tr><th>Email</th><td><input type="text" name="email" value=""/></td></tr>
<tr><th>Phone</th><td><input type="text" name="phone" value=""/></td></tr>
<tr><th>Address_1</th><td><input type="text" name="address_1" value=""/></td></tr>
<tr><th>Address_2</th><td><input type="text" name="address_2" value=""/></td></tr>
<tr><th>City</th><td><input type="text" name="city" value=""/></td></tr>
<tr><th>State</th><td><input type="text" name="state" value=""/></td></tr>
<tr><th>Country</th><td><input type="text" name="country" value=""/></td></tr>

</table>
<input type='submit' name="save" value='Add' class='button button-primary'> &nbsp;&nbsp;
</form>


</div>
<?php
}
?>