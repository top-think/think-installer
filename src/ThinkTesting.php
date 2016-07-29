<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\composer;


use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ThinkTesting extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        if ('topthink/think-testing' !== $package->getPrettyName()) {
            throw new \InvalidArgumentException('Unable to install this library!');
        }

        return parent::getInstallPath($package);
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        //copy tests dir
        $appDir = dirname($this->vendorDir);
        if (!is_file($appDir . DIRECTORY_SEPARATOR . 'phpunit.xml')) {

            $it = new RecursiveDirectoryIterator($this->getInstallPath($package), RecursiveDirectoryIterator::SKIP_DOTS);
            $ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);

            foreach ($ri as $file) {
                $targetPath = $appDir . DIRECTORY_SEPARATOR . $ri->getSubPathName();
                if ($file->isDir()) {
                    $this->filesystem->ensureDirectoryExists($targetPath);
                } else {
                    copy($file->getPathname(), $targetPath);
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'think-testing' === $packageType;
    }
}