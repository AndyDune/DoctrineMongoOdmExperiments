<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 05.06.2018                            |
 * -----------------------------------------------
 *
 */
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ODM\MongoDB\Types\Type;

$file = __DIR__ . '/../vendor/autoload.php';

if (! file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$loader = require $file;
$loader->add('AndyDune\DoctrineMongoOdmExperimentsTest', __DIR__ . '/../test');

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

Type::addType('date_andydune', \AndyDune\DoctrineMongoOdmExperiments\Types\DateAndyDune::class);