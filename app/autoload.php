<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('My\\Namespace', __DIR__.'/../vendor/dom');


AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
