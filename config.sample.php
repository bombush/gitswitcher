<?php
define('PATH_GIT_REPO', '/home/projects/eclimbkonfigo.konfigo');
define('PATH_GITSWITCHER', dirname(__FILE__));
define('BRANCH_DEFAULT', 'TEST');
define('GITSWITCHER_PASSWORD', ''); //md5hash

/*
 *	user to run git commands as. If left empty, run sudo as root.
 *
 * In order for this to work, the sudoers file needs to be edited correctly.
 *
 * EXAMPLE(To run git commands as user assembla):
 * 	// run command: 
 * 		$ visudo
 *  // edit sudoers file to include (allow user www-data to sudo git as user assembla without password): 
 *  	www-data ALL=(assembla) NOPASSWD: /usr/bin/git
 * 
 */
define('SUDO_USER', ''); // the username to SUDO to
define('REQUIRE_SUDO', true); // use sudo for GIT operations

define('REQUIRE_CLEAN_REPO', true); //only perform GIT operation on a clean repository

