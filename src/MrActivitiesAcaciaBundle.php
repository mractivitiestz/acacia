<?php

declare(strict_types=1);

namespace MrActivities\Acacia;

use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Exposes assets/ to AssetMapper under the namespace @mractivities/acacia, so a layout links
 * {{ asset('@mractivities/acacia/acacia.css') }}. The bundle defines no services.
 *
 * Docs: https://symfony.com/doc/current/frontend/create_ux_bundle.html ("Specifics for Asset Mapper": prepend
 * framework.asset_mapper.paths with the bundle's assets directory and a namespace) and
 * https://symfony.com/doc/current/frontend/asset_mapper.html#framework-asset-mapper-paths ("give each path a namespace").
 * Patterned on vendor/symfony/ux-turbo/src/DependencyInjection/TurboExtension.php (prepend() and isAssetMapperAvailable()).
 */
final class MrActivitiesAcaciaBundle extends AbstractBundle
{
    public const string ASSET_NAMESPACE = '@mractivities/acacia';

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (!$this->isAssetMapperAvailable($builder)) {
            return;
        }

        $builder->prependExtensionConfig('framework', [
            'asset_mapper' => [
                'paths' => [
                    \dirname(__DIR__).'/assets' => self::ASSET_NAMESPACE,
                ],
            ],
        ]);
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    private function isAssetMapperAvailable(ContainerBuilder $builder): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            return false;
        }

        $bundles = $builder->getParameter('kernel.bundles_metadata');
        if (!\is_array($bundles) || !\is_array($bundles['FrameworkBundle'] ?? null)) {
            return false;
        }
        $frameworkPath = $bundles['FrameworkBundle']['path'] ?? null;

        return isset($bundles['AssetMapperBundle'])
            || (\is_string($frameworkPath) && is_file($frameworkPath.'/Resources/config/asset_mapper.php'));
    }
}
