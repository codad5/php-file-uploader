<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite1c69a82de86b490c72e236c594ce11f
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Codad5\\FileHelper\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Codad5\\FileHelper\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite1c69a82de86b490c72e236c594ce11f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite1c69a82de86b490c72e236c594ce11f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite1c69a82de86b490c72e236c594ce11f::$classMap;

        }, null, ClassLoader::class);
    }
}