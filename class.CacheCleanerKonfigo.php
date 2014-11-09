<?php

class CacheCleanerKonfigo extends CacheCleanerBase
{
	const CC_NETTE_CACHE_ROOT = '/mnt/alcaeus-sda8/www/eclimbkonfigo.konfigo/temp/cache/'; 

	public function clean()
	{
		$this->cleanNetteCache(self::CC_NETTE_CACHE_ROOT);
	}
}