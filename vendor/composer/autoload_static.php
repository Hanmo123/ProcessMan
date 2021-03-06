<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit78e580a707dc40f8f660ea149251cb9b
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit78e580a707dc40f8f660ea149251cb9b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit78e580a707dc40f8f660ea149251cb9b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit78e580a707dc40f8f660ea149251cb9b::$classMap;

        }, null, ClassLoader::class);
    }
}
