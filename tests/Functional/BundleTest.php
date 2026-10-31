<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Functional;

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

        $asset = $mapper->getAsset('@mractivities/acacia/acacia.css');

        self::assertNotNull($asset);
        self::assertSame(realpath(__DIR__.'/../../assets/acacia.css'), realpath($asset->sourcePath));
        self::assertStringStartsWith('/assets/@mractivities/acacia/acacia-', $asset->publicPath);
    }

    public function testAComponentFileResolvesToo(): void
    {
        $mapper = self::getContainer()->get('asset_mapper');
        self::assertInstanceOf(AssetMapperInterface::class, $mapper);

        self::assertNotNull($mapper->getAsset('@mractivities/acacia/components/button.css'));
    }
}
