<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Command',
        __DIR__ . '/Config',
        __DIR__ . '/Controller',
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/Event',
        __DIR__ . '/Exception',
        __DIR__ . '/Execution',
        __DIR__ . '/Field',
        __DIR__ . '/Resources',
        __DIR__ . '/Security',
        __DIR__ . '/Tests',
    ])
    ->withPhpSets(php83: true)
    ->withSets([
        PHPUnitSetList::PHPUNIT_50,
        PHPUnitSetList::PHPUNIT_60,
        PHPUnitSetList::PHPUNIT_70,
        PHPUnitSetList::PHPUNIT_80,
        PHPUnitSetList::PHPUNIT_90,
    ])    ->withSkip([
        \Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector::class,
        \Rector\Php83\Rector\ClassConst\AddTypeToConstRector::class,
        \Rector\Php82\Rector\Class_\ReadOnlyClassRector::class,
    ]);
