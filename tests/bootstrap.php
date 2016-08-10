<?php

if (! is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \RuntimeException('Did not find vendor/autoload.php. Did you run "composer install --dev"?');
}

require_once __DIR__.'/../vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace('Doctrine\Common\Annotations');
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
