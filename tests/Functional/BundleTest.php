<?php

declare(strict_types=1);

namespace MrActivities\DesignSystem\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\AssetMapper\AssetMapperInterface;

final class BundleTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testAssetMapperResolvesTheEntryUnderThePackageNamespace(): void
    {
        $mapper = self::getContainer()->get('asset_mapper');
        self::assertInstanceOf(AssetMapperInterface::class, $mapper);

        $asset = $mapper->getAsset('@mractivities/design-system/mractivities.css');

        self::assertNotNull($asset);
        self::assertSame(realpath(__DIR__.'/../../assets/mractivities.css'), realpath($asset->sourcePath));
        self::assertStringStartsWith('/assets/@mractivities/design-system/mractivities-', $asset->publicPath);
    }

    public function testAComponentFileResolvesToo(): void
    {
        $mapper = self::getContainer()->get('asset_mapper');
        self::assertInstanceOf(AssetMapperInterface::class, $mapper);

        self::assertNotNull($mapper->getAsset('@mractivities/design-system/components/button.css'));
    }
}
