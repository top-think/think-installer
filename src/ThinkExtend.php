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

class ThinkExtend extends LibraryInstaller
{

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);
        $this->copyExtraFiles($package);
    }

    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);
        $this->copyExtraFiles($target);

    }

    protected function copyExtraFiles(PackageInterface $package)
    {
        $extra = $package->getExtra();

        if (!empty($extra['files'])) {

            $composerExtra = $this->composer->getPackage()->getExtra();
            $extraDir      = (!empty($composerExtra['app-path']) ? $composerExtra['app-path'] : 'application') . DIRECTORY_SEPARATOR . 'extra';

            foreach ((array) $extra['files'] as $file) {
                $name   = pathinfo($file, PATHINFO_BASENAME);
                $target = $extraDir . DIRECTORY_SEPARATOR . $name;
                $source = $this->getInstallPath($package) . DIRECTORY_SEPARATOR . $file;
                if (is_file($source) && !is_file($target)) {
                    copy($source, $target);
                }
            }
        }
    }

    public function supports($packageType)
    {
        return 'think-extend' === $packageType;
    }
}