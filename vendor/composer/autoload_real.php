<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit81b7f6bfb816fd508ac2f2a38a6d2a60
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit81b7f6bfb816fd508ac2f2a38a6d2a60', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit81b7f6bfb816fd508ac2f2a38a6d2a60', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit81b7f6bfb816fd508ac2f2a38a6d2a60::getInitializer($loader));

        $loader->setClassMapAuthoritative(true);
        $loader->register(true);

        $includeFiles = \Composer\Autoload\ComposerStaticInit81b7f6bfb816fd508ac2f2a38a6d2a60::$files;
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire81b7f6bfb816fd508ac2f2a38a6d2a60($fileIdentifier, $file);
        }

        return $loader;
    }
}

/**
 * @param string $fileIdentifier
 * @param string $file
 * @return void
 */
function composerRequire81b7f6bfb816fd508ac2f2a38a6d2a60($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

        require $file;
    }
}
