<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

$magicQuotesEnabled = (bool) ini_get('magic_quotes_gpc');
if ($magicQuotesEnabled === true) {
	superglobal_strip_slashes();
}

if (file_exists('offline.html')) {
    readfile('offline.html');
    die();
}

require 'app/config/config.php';
$paths = implode(
	PATH_SEPARATOR,
	array(
		$systemconfig['path']['library'],
		$systemconfig['path']['app'],
		$systemconfig['path']['engine'],
		$systemconfig['path']['system']
	)
);
set_include_path($paths);
require 'Bootstrap.php';

$bootstrap = new Bootstrap();
$bootstrap->run($systemconfig);

function superglobal_strip_slashes()
{
	if (isset($_GET) && !empty($_GET)) {
		strip_slashes_recursive($_GET);
	}
	if (isset($_POST) && !empty($_POST)) {
		strip_slashes_recursive($_POST);
	}
	if (isset($_COOKIE) && !empty($_COOKIE)) {
		strip_slashes_recursive($_COOKIE);
	}
}

function strip_slashes_recursive(&$value)
{
	$value = is_array($value) ? array_map('strip_slashes_recursive', $value) : stripslashes($value);
	return $value;
}
