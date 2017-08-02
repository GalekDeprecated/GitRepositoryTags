<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Galek
 * Date: 2.8.2017
 * Time: 13:57
 */

namespace Galek\GitRepositoryTags;

use Nette;

/**
 * @property-read array $tags
 * @property-read array $versions
 * @property-read string $latestTag
 * @property-read string $latestVersion
 */
class GitRepositoryTags
{
	use Nette\SmartObject;

	private $pathGitTags = '.git/refs/tags';

	/** @var string */
	private $path;

	/** @var array */
	private $tags;

	/** @var string */
	private $latestTag;

	/** @var array */
	private $versions;

	/** @var string */
	private $latestVersion;

	/** @var string */
	private $versionPrefix = 'v';


	public function __construct($directory, $versionPrefix = 'v')
	{
		$this->path = $directory . '/' . $this->pathGitTags;

		$this->versionPrefix = $versionPrefix;

		if (!is_dir($this->path)) {
			throw new DirectoryNotFoundException("Not found git directory in '$directory' ");
		}

		$this->init();
	}


	private function init()
	{
		/**
		 * @var string $key
		 * @var SplFileInfo $file
		 */
		foreach (Nette\Utils\Finder::findFiles('*')->in($this->path) as $key => $file) {
			$this->tags[] = $file->getFilename();

			if (Nette\Utils\Strings::startsWith($file->getFilename(), $this->versionPrefix)) {
				$this->versions[] = $file->getFilename();
			}
		}

		if (!empty($this->tags)) {
			natsort($this->tags);

			$this->latestTag = end($this->tags);


			if (!empty($this->versions)) {
				natsort($this->versions);

				$this->latestVersion = end($this->versions);
			}
		}
	}


	/**
	 * @return array
	 */
	public function getTags()
	{
		return !empty($this->tags) ? $this->tags : [];
	}


	/**
	 * @return array
	 */
	public function getVersions()
	{
		return !empty($this->versions) ? $this->versions : [];
	}


	/**
	 * @return string|null
	 */
	public function getLatestTag()
	{
		return $this->latestTag;
	}


	/**
	 * @return string|null
	 */
	public function getLatestVersion()
	{
		return $this->latestVersion;
	}
}
