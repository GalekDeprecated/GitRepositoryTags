<?php
/**
 * Created by PhpStorm.
 * User: Galek
 * Date: 2.8.2017
 * Time: 14:25
 */
namespace Galek\GitRepositoryTags\DI;

use Nette\DI\CompilerExtension;

class GitRepositoryTagsExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$config = $this->getConfig([
			'directory' => null,
			'versionPrefix' => 'v'
		]);

		$builder->addDefinition($this->prefix('gitRepositoryTags'))
			->setClass('App\Galek\Utils\GitRepositoryTags\GitRepositoryTags', $config);
	}
}
