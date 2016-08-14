<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
//Enregistrement d'un namespace installé manuellement
$loader->add('Elastica',__DIR__.'/../vendor/bundles/elastica/lib');
$loader->add('FOQ', __DIR__.'/../vendor/bundles');
$loader->add('BCC', __DIR__.'/../vendor/bundles');
$loader->add('CCDNUser', __DIR__.'/../vendor/bundles');

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
