<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\Set\ValueObject\SetList;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Ssch\TYPO3Rector\Rules\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/config/config.php');

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__ . '/phpstan.neon');

    $services = $containerConfigurator->services();

    $services->set(AddCodeCoverageIgnoreToMethodRectorDefinitionRector::class);
    $services->set(AddSeeTestAnnotationRector::class);
    $services->set(VarConstantCommentRector::class);

    $parameters->set(Option::PATHS, [__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests']);

    $containerConfigurator->import(SetList::PRIVATIZATION);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::CODE_QUALITY);

    $parameters->set(
        Option::SKIP,
        [
            __DIR__ . '/src/Rector/v8/v6/RefactorTCARector.php',
            RemovePackageVersionsRector::class => [__DIR__ . '/config', __DIR__ . '/tests'],
            __DIR__ . '/src/Set',
            '*/Fixture/*',
            StringClassNameToClassConstantRector::class => [
                // accidental local class autoload in require-dev
                __DIR__ . '/src/Rector/v9/v5/ExtbaseCommandControllerToSymfonyCommand/AddArgumentToSymfonyCommandRector.php',
            ],
        ]
    );

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);
    $parameters->set(Option::ENABLE_CACHE, true);
};
