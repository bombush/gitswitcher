<?php

abstract class CacheCleanerBase
{
	const CC_NETTE_CACHE_ROOT = '/temp/cache/';
	protected $_CC_NETTE_CACHE_TRASH;


	public function __construct()
	{
		$this->init();
	}

	protected function init()
	{
		$this->_CC_NETTE_CACHE_TRASH = self::CC_NETTE_CACHE_ROOT . 'gitswitcher_trash/';
	}

	// Moves contents of the cache dir to a temporary trash dir (in case we messed up the cache path)
	protected function cleanNetteCache($path = self::CC_NETTE_CACHE_ROOT)
	{	
		//create trash root if not exists
		if(!is_dir($this->_CC_NETTE_CACHE_TRASH)){
			if(!mkdir($this->_CC_NETTE_CACHE_TRASH)){
				addToGitswitcherOutput('Failed to create a trash directory in :'.$this->_CC_NETTE_CACHE_TRASH);
			} else {
				addToGitswitcherOutput('Created a trash directory in :'.$this->_CC_NETTE_CACHE_TRASH);
			}
		}

		//create trash dir for the current operation
		$timestamp = date('YmdHis');
		$timestamped_trash = $this->_CC_NETTE_CACHE_TRASH.'trash_'.$timestamp;
		if(!mkdir($timestamped_trash)){
				addToGitswitcherOutput('Failed to create a trash directory in :'.$timestamped_trash);
		} else {
			addToGitswitcherOutput('Created a trash directory in :'.$timestamped_trash);
		}

		//exec
		exec('mv '.self::CC_NETTE_CACHE_ROOT.'* '.$timestamped_trash.'/ -Rv', $output);
		addToGitswitcherOutput('[MOVING CACHE TO TRASH]');
		addToGitswitcherOutput($output);
	}

	protected function cleanMemcache()
	{

	}

	//call all the cleaning functions for the current project
	abstract public function clean();
}