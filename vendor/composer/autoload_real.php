<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitcd62eb0a029f35cbc79f91cdafc28418
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

        spl_autoload_register(array('ComposerAutoloaderInitcd62eb0a029f35cbc79f91cdafc28418', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitcd62eb0a029f35cbc79f91cdafc28418', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitcd62eb0a029f35cbc79f91cdafc28418::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
