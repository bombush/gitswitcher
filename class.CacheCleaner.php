<?php

class CacheCleanerKonfigo extends CacheCleanerBase
{
	public function clean()
	{
		$this->cleanNetteCache();
	}
}