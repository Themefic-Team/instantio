<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit068514c6ada71ea55e09706d957e358c
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'INS\\Includes\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'INS\\Includes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit068514c6ada71ea55e09706d957e358c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit068514c6ada71ea55e09706d957e358c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit068514c6ada71ea55e09706d957e358c::$classMap;

        }, null, ClassLoader::class);
    }
}
