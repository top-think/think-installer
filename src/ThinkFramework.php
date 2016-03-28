<?php

namespace think\composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class ThinkFramework extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getPackageBasePath(PackageInterface $package)
    {
        if ('topthink/framework' !== $package->getPrettyName()) {
            throw new \InvalidArgumentException('Unable to install this library!');
        }

        return 'thinkphp';
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'think-framework' === $packageType;
    }
}