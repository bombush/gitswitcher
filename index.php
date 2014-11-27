<?php 
require_once('config.php');
require_once('functions.php');

session_start();
if(!isset($_SESSION['logged']) && (!isset($_POST['password']) || md5($_POST['password']) != GITSWITCHER_PASSWORD)){ //password md5
?>
	<form method="POST">
	<input type="password" name="password" autofocus>
	<input type="submit" name="login" value="login">
	</form>
<?php


} else {
	//user is logged in, display some stuff
$_SESSION['logged'] = true; 

init();

?>

<?php
//process actions submitted in the form
	if(isset($_POST['action'])){
		processAction($_POST['action']);
	}
?>

<b>Git output</b>
<div class="console-output" style="border: 1px solid #aaaaaa; min-height: 50px; max-height: 600px; overflow: scroll;">
<?php
	printOutput();
?>
</div>
<p/>
<p/>

<?php
//get and display some branch info
$branches = getBranchesInfo();
$active_branch = getActiveName($branches);
$branches_select = getBranchesToSelect($branches);
?>

<div class="current_branch">
<u>Current branch:</u> <b> <?php echo $active_branch?></b>
</div>
<p/>
<p/>

<?php

//server is reserved for testing, cannot manipulate git
if(isReservationActive()){
?>
	<font color="red">You cannot switch branches because the server is currently reserved for testing.</font><br>
	<b>Message:</b> <?php echo getReservationMessage();?><br>
	<p/>
	<form method="post">
		<input type="hidden" value="unreserve" name="action" />
			<b>Unreserve?</b> (Branch will be switched to default(<b><?php echo BRANCH_DEFAULT;?></b>))
		<input type="submit" value="Yes" />
	</form>

<?php } else {

	//it is ok to do whatever we want
?>

<?//switch branch dialog?>
<b>Switch branch</b>
<div style="border: 1px solid #aaaaaa; min-height: 50px;"> 
<form method="post">
	<select name="branch_name"> 
	<optgroup label="local">
	<?php
	foreach($branches_select['local'] as $branch){
		echo '<option value="'.$branch['name'].'"'.($branch['active']?'selected="selected"':'').'>'.$branch['name'].'</option>';
	}
	?>
	</optgroup>
	<optgroup label="remote">
	<?php
	foreach($branches_select['remote'] as $branch){
		echo '<option value="'.$branch['fullname'].'"'.($branch['active']?'selected="selected"':'').'>'.$branch['fullname'].'</option>';
	}
	?>
	</optgroup>
	</select>
	<input type="checkbox" name="reserve" value="true">Reserve for testing</input>
	<input type="text" name="reservation_message" value="Reservation message" />
	<input type="submit" value="Checkout branch" />
	<input type="hidden" name="action" value="checkout_branch" />
</form>
</div>

<?php
//new branch dialog
/*
<b>New branch</b>
<div style="border: 1px solid #aaaaaa; min-height: 50px;">
<form method="post">
	<input type="text" name="branch_name"></input>
	<input type="submit" value="New branch"></input>
	<input type="hidden" name="action" value="new_branch" />
</form>
</div>
*/?>

<?php
}

}?>