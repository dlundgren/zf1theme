<?php
/*
 * Set error reporting to the level to which Zend Framework code must comply.
 */
error_reporting(E_ALL | E_STRICT);

// Suppress DateTime warnings
date_default_timezone_set(@date_default_timezone_get() ?: 'UTC');

// setup a root for future uses by the tests
$vfs = org\bovigo\vfs\vfsStream::setup('root');