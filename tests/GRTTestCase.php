<?php
/**
 * Created by PhpStorm.
 * User: Galek
 * Date: 2.8.2017
 * Time: 14:59
 */

$container = require __DIR__ . '/bootstrap.php';

abstract class GRTTestCase extends Tester\TestCase
{
	public $directory = __DIR__ . "/temp";
}
