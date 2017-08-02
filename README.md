# Git Repository Tags

[![Build Status](https://travis-ci.org/JanGalek/GitRepositoryTags.svg?branch=master)](https://travis-ci.org/JanGalek/GitRepositoryTags)
[![Total Downloads](https://poser.pugx.org/galek/git-repository-tags/downloads)](https://packagist.org/packages/galek/git-repository-tags)
[![Latest Stable Version](https://poser.pugx.org/galek/git-repository-tags/v/stable)](https://packagist.org/packages/galek/git-repository-tags)
[![License](https://poser.pugx.org/galek/git-repository-tags/license)](https://packagist.org/packages/galek/git-repository-tags)
[![Monthly Downloads](https://poser.pugx.org/galek/git-repository-tags/d/monthly)](https://packagist.org/packages/galek/git-repository-tags)

Package Installation
--------------------

The best way to install Git Repository Tags is using [Composer](http://getcomposer.org/):

```sh
$ composer require galek/git-repository-tags
```

Basic usage:
----------

```php
$directory = __DIR__ . '/../'; // where we have directory `.git`
$versionPrefix = 'v'; // prefix for version, default is v, so v1.0.0
$gitTags = new \Galek\GitRepositoryTags\GitRepositoryTags($directory, $versionPrefix);

// gets informations:

$tags = $gitTags->tags; // array of everyone tags
$versions = $gitTags->versions; // array of everyone tags with our version prefix
$latestVersion = $git->tags->latestVersion; // string, get full name of latest version
```

Nette usage:
------------

config.neon
```neon
extensions: 
    gitTags: Galek\GitRepositoryTags\DI\GitRepositoryTagsExtension

gitTags:
	directory: %appDir%/../
	versionPrefix: 'v'
```

Presenter
```php
class BasePresenter extends Presenter
{
    /** @var \Galek\GitRepositoryTags\GitRepositoryTags @inject */
    public $gitTags;
    
    public function renderDefault()
    {
        $this->template->version = $this->gitTags->latestVersion;
    }
}

```
