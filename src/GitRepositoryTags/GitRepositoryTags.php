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
use SplFileInfo;

/**
 * @property-read array $tags
 * @property-read array $versions
 * @property-read string $latestTag
 * @property-read string $latestVersion
 * @property-read string|null $currentVersion
 */
class GitRepositoryTags
{
	use Nette\SmartObject;

	private $pathGitTags = 'refs/tags';

	private $pathGitHead = 'HEAD';

	/** @var string */
	private $path;

	/** @var string */
	private $gitPath;

	/** @var string */
	private $pathHead;

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

	/** @var bool */
	private $byCurrentBranch;

	/** @var string|null */
	private $currentVersion;


	public function __construct($directory, $versionPrefix = 'v', $byCurrentBranch = false)
	{
		$this->byCurrentBranch = $byCurrentBranch;

		$this->gitPath = realpath($directory) . '/.git';
		$this->path = $this->gitPath . '/' . $this->pathGitTags;
		$this->pathHead = $this->gitPath . '/' . $this->pathGitHead;

		$this->versionPrefix = $versionPrefix;

		if (!is_dir($this->path)) {
			throw new DirectoryNotFoundException("Not found git directory in '$this->path' '$directory' '$this->gitPath' ");
		}

		if (!is_file($this->pathHead)) {
			throw new DirectoryNotFoundException("Not found git HEAD '$this->pathHead' ");
		}

		$this->init();
	}


	private function init()
	{
		if ($this->byCurrentBranch) {
			$currentCommitId = $this->getCurrentCommitId();
		}
		/**
		 * @var string $key
		 * @var SplFileInfo $file
		 */
		foreach (Nette\Utils\Finder::findFiles('*')->in($this->path) as $key => $file) {
			$this->tags[] = $file->getFilename();

			if ($this->byCurrentBranch) {
				$commitId = $this->readFile($key);

				if ($commitId == $currentCommitId) {
					$this->currentVersion = $file->getFilename();
				}
			}

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

				if ($this->byCurrentBranch && !$this->currentVersion) {
					$this->currentVersion = $this->latestVersion;
				}

			} else {
				if ($this->byCurrentBranch && !$this->currentVersion) {
					$this->currentVersion = $this->getCurrentCommitId();
				}
			}
		} else {
			if ($this->byCurrentBranch && !$this->currentVersion) {
				$this->currentVersion = $this->getCurrentCommitId();
			}
		}
	}


	private function getCurrentCommitId()
	{
		if (($ref = $this->getHeadRef()) !== null) {
			return $this->readFile($this->gitPath . '/' . $ref);
		} else {
			return $this->getHeadRef(true);
		}

		return null;
	}


	private function getHeadRef($c = false)
	{
		$rep = $this->readFile($this->pathHead);

		preg_match("/ref: (.*)?/", $rep, $match);

		if ($c === true) {
			if (empty($match)) {
				return $rep;
			}
		}

		$ref = null;

		if (isset($match[1])) {
			$ref = $match[1];
		}

		return $ref;
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


	/**
	 * @return string|null
	 */
	public function getCurrentVersion()
	{
		$this->init();
		return $this->currentVersion;
	}


	private function readFile(string $file)
	{
		$handle = fopen('nette.safe://' . realpath($file), 'r');
		$content = fread($handle, filesize($file));
		fclose($handle);

		return $content;
	}
}
