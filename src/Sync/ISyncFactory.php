<?php namespace SRAG\ILIAS\Plugins\Hub2\Sync;

/**
 * Interface ISyncFactory
 * @package SRAG\ILIAS\Plugins\Hub2\Sync
 */
interface ISyncFactory {

	/**
	 * Returns an iterator which can be used to iterate over all hub user objects.
	 *
	 * @return \Iterator
	 */
	public function users();

	/**
	 * Returns an iterator of users which will load the users in batches with the given $size.
	 * E.g. calling $factory->usersBatch(500) will only load users 0-499 and automatically load users 500-n
	 *
	 * @param int $size Size of the batches
	 * @return \Iterator
	 */
	public function usersBatch($size);


	public function courses();

	public function coursesBatch($batch_size);

	public function categories();

	public function categoriesBatch($batch_size);
}