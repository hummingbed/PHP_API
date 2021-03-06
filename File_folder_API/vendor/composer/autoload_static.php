<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1f65791bbe0580150d7ec469758f12cf
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1f65791bbe0580150d7ec469758f12cf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1f65791bbe0580150d7ec469758f12cf::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1f65791bbe0580150d7ec469758f12cf::$classMap;

        }, null, ClassLoader::class);
    }
}
