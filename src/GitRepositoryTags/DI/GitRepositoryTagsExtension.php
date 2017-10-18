<?php
declare(strict_types=1);

namespace Galek\GitRepositoryTags\DI;

use Nette\DI\CompilerExtension;

class GitRepositoryTagsExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$config = $this->getConfig([
			'directory' => null,
			'versionPrefix' => 'v',
			'byCurrentBranch' => false,
		]);

		$builder->addDefinition($this->prefix('gitRepositoryTags'))
			->setClass('Galek\GitRepositoryTags\GitRepositoryTags', $config);
	}
}
