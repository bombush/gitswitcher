<?php
define('PATH_GIT_REPO', '/home/projects/eclimbkonfigo.konfigo');
define('PATH_GITSWITCHER', dirname(__FILE__));
define('BRANCH_DEFAULT', 'TEST');
define('GITSWITCHER_PASSWORD', ''); //md5hash

//cache cleaner
define('CACHE_CLEANER_CLASS', 'CacheCleanerKonfigo');

//user to run git commands as. If left empty, run sudo as root
define('SUDO_USER', '');

//includes
$_CONFIG_INCLUDES = array('class.CacheCleanerBase.php');