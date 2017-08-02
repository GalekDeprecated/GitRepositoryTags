<?php
declare(strict_types=1);
require_once __DIR__ . '/GRTTestCase.php';

require __DIR__ . '/../src/GitRepositoryTags/GitRepositoryTags.php';

use Galek\GitRepositoryTags\GitRepositoryTags;
use Tester\Assert;

class BasicTest extends GRTTestCase
{
	public function testOne()
	{
		$grt = new GitRepositoryTags($this->directory);

		Assert::equal('v20.1.0', $grt->latestVersion);

		Assert::equal('zend', $grt->latestTag);

		Assert::equal(['release', 'v1.0.0', 'v1.1.0', 'v20.1.0', 'zend'], $grt->tags);

		Assert::equal(['v1.0.0', 'v1.1.0', 'v20.1.0'], $grt->versions);
	}


	public function testNotFoundException()
	{
		$e = Assert::exception(function () {
			$grt = new GitRepositoryTags(__DIR__);
		}, 'Galek\GitRepositoryTags\DirectoryNotFoundException');

		Assert::type('Galek\GitRepositoryTags\DirectoryNotFoundException', $e);
	}
}

(new BasicTest())->run();
