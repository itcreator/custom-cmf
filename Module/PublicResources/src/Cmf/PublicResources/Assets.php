<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\PublicResources;

use Cmf\System\Application;
use Cmf\View\Theme\ThemeFactory;

/**
 * Create sym links for css and js folders
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Assets
{
    /**
     * @return $this
     */
    public function install()
    {
        $this
            ->installForModules()
            ->installForThemes();

        $this->installJquery();//TODO: remove it

        return $this;
    }

    /**
     * @return $this
     */
    protected function installJquery()
    {
        //TODO: refactor it. make custom assets
        $target = ROOT . 'vendor/frameworks/jquery';

        $ok = true;

        $link = ROOT . 'public/frameworks/jquery';
        if ($ok && file_exists($link)) {
            $ok = false;
        }

        $this->createDir('frameworks');

        if ($ok) {
            symlink($target, $link);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function installForModules()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\PublicResources', 'resources');

        foreach ($config as $key => $item) {
            $path = is_string($item) ? $item : null;

            $moduleName = str_replace('-', '\\', $key);
            $this->createModuleSymLink($moduleName, 'css', $path);
            $this->createModuleSymLink($moduleName, 'js', $path);
        }

        return $this;
    }

    /**
     * @param \DirectoryIterator $fileInfo
     * @param string $folder
     * @return $this
     */
    protected function createThemeSymLink(\DirectoryIterator $fileInfo, $folder)
    {
        $target = $fileInfo->getPathname() . '/' . $folder;

        $ok = true;
        if (!is_dir($target)) {
            $ok = false;
        }

        $link = ROOT . 'public/theme/' . $fileInfo->getFilename() . '/' . $folder;
        if ($ok && file_exists($link)) {
            $ok = false;
        }

        $this->createDir('theme/' . $fileInfo->getFilename());

        if ($ok) {
            symlink($target, $link);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function installForThemes()
    {
        $iterator = new \DirectoryIterator(ROOT . ThemeFactory::THEME_FOLDER);
        foreach ($iterator as $fileInfo) {
            /** @var \DirectoryIterator $fileInfo */
            if ($fileInfo->isDot()) {
                continue;
            }

            $this->createThemeSymLink($fileInfo, 'css');
            $this->createThemeSymLink($fileInfo, 'js');
            $this->createThemeSymLink($fileInfo, 'jquery.min.js'); //TODO: remove it
        }

        return $this;
    }

    /**
     * @param string $moduleName
     * @param string $folder
     * @param string|null $path
     * @return $this
     */
    public function createModuleSymLink($moduleName, $folder, $path = null)
    {
        $mm = Application::getModuleManager();
        if ($path) {
            $target = ROOT . $path . $folder;
        } else {
            $target = $mm->getModulePath($moduleName) . '/' . ThemeFactory::THEME_FOLDER . $folder;
        }

        $ok = true;
        if (!is_dir($target)) {
            $ok = false;
        }

        $modulePath = str_replace('\\', '/', $moduleName) . '/';
        $link = ROOT . 'public/' . $modulePath . $folder;
        if ($ok && file_exists($link)) {
            $ok = false;
        }

        $this->createDir($modulePath);
        if ($ok) {
            symlink($target, $link);
        }

        return $this;
    }

    /**
     * @param string $dir
     * @return $this
     */
    public function createDir($dir)
    {
        $path = ROOT . 'public/' . $dir;
        if (!is_dir($path)) {
            mkdir($path, 0744, true);
        }

        return $this;
    }
}
