<?php
$GIT_OUTPUT = array();

function init()
{
	chdir(PATH_GIT_REPO);

	if(!isRepoClean()){
		echo "<font color=\"red\">Cannot proceed because the repository is not clean</font><br/>";
		printOutput();
		exit;
	}

	//fetch if first visit in a session
	if(!isset($_POST['action'])){
		gitFetch();
	}
}


function processAction($action)
{
	switch($action){
		case 'new_branch':
			createNewBranch();
			break;
		case 'checkout_branch':
			checkoutBranch();
			break;
		case 'unreserve':
			unreserve();
	}
}

//GIT

function createNewBranch()
{
	$branch_name = sanitizeCommand($_POST['branch_name']);
	addToOutput(nl2br(shell_exec('sudo git branch '.$branch_name . ' 2>&1')));
}

function checkoutBranch()
{
	$branch_name = sanitizeCommand($_POST['branch_name']);
	
	$reserve = isset($_POST['reserve']);
	if($reserve)
		reserveForTesting($_POST['reservation_message']);

	gitCheckout($branch_name);
}


function switchToDefaultBranch()
{
	gitCheckout(BRANCH_DEFAULT);
}

function gitCheckout($branch_name)
{
	//checkout and pull
	$command1 = 'sudo git checkout '.getBranchBaseNameClean($branch_name).' 2>&1';
	addToOutput(shell_exec($command1));
	$command2 = 'sudo git pull 2>&1';
	addToOutput(shell_exec($command2));
}

function gitFetch()
{
	addToOutput(shell_exec('sudo git fetch -v 2>&1'));
}

function getBranchesInfo()
{
	$branches = shell_exec('sudo git branch -a 2>&1');

	$branches = explode(PHP_EOL, $branches);

	$branches_with_info = array();
	for($i = 0; $i < count($branches); $i++) {
		if($branches[$i] === '')
			continue;

		$branches_with_info[$i]['fullname'] = getBranchFullNameClean($branches[$i]);
		$branches_with_info[$i]['name'] = getBranchBaseNameClean($branches[$i]);
		$branches_with_info[$i]['active'] = isActiveBranch($branches[$i]);
		$branches_with_info[$i]['remote'] = isOriginBranch($branches[$i]);
	}

	return $branches_with_info;
}

function isRepoClean()
{
	$status = shell_exec('sudo git status 2>&1');
	if(!strstr($status, 'working directory clean')){
		addToOutput($status);

	} else {
		return TRUE;
	}
}

//RESERVATIONS
function reserveForTesting($msg)
{
	file_put_contents(PATH_GITSWITCHER.'/.git_reservation', strip_tags($msg));
}

function unreserve()
{
	unlink(PATH_GITSWITCHER.'/.git_reservation');
	switchToDefaultBranch();
}

function isReservationActive()
{
	return file_exists(PATH_GITSWITCHER.'/.git_reservation');
}

function getReservationMessage()
{
	return file_get_contents(PATH_GITSWITCHER.'/.git_reservation');
}

function sanitizeCommand($input)
{
	$regex = '/[ ;]+/';
	if(preg_match($regex, $input))
		exit('regex fail');

	return strip_tags($input);
}

//BRANCH PARSING

//parses out the base branch name
function getBranchBaseNameClean($branch_full)
{
	$branch_full = getBranchFullNameClean($branch_full);
	$branch_components = explode('/', $branch_full);
	return trim($branch_components[count($branch_components)-1]);
}

function getBranchFullNameClean($branch_full)
{
	return ltrim(trim($branch_full),'*');
}

function isActiveBranch($branch_full)
{
	return (substr($branch_full,0,1) === '*');
}

function isOriginBranch($branch_full)
{
	$branch_components = explode('/', trim($branch_full));

	if($branch_components[0] === 'remotes' && $branch_components[1] === 'origin')
		return TRUE;
	else 
		return FALSE;
}

//gets local branches and remote branches without local copy
function getBranchesToSelect($branches)
{
	$selected_branches = array('local' => array(), 'remotes' => array());
	$local_names = array();

	foreach($branches as $branch)
	{
		if($branch['remote'] === FALSE){
			$selected_branches['local'][] = $branch;
			$local_names[] = $branch['name'];
		}
		else{
			if(in_array($branch['name'], $local_names))
				continue;

			$selected_branches['remote'][] = $branch;
		}
	}

	return $selected_branches;
}

function getActiveName($branches){
	foreach($branches as $branch){
		if($branch['active'] === TRUE)
			return $branch['name'];
	}
}

function addToOutput($string)
{
	global $GIT_OUTPUT;
	$GIT_OUTPUT[] = $string;
}

function printOutput()
{
	global $GIT_OUTPUT;
	foreach($GIT_OUTPUT as $block){
		echo nl2br($block);
		echo "<br>";
	}
}