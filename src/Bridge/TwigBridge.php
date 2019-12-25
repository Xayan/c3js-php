<?php

namespace C3\Bridge;

use C3\Exception\TwigException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * TwigBridge class manages initialization of Twig Environment. It also allows to provide already existing Environment,
 * For example when using inside of a project that already uses Twig.
 *
 * @package C3\Bridge
 */
class TwigBridge
{
    /**
     * @var Environment
     */
    private static $environment;

    /**
     * @return bool
     */
    public static function exists(): bool
    {
        return class_exists(Environment::class);
    }

    /**
     * @return void
     * @throws TwigException
     */
    public static function init(): void
    {
        if(self::$environment === null) {
            if(!self::exists()) {
                throw new TwigException("Twig not found");
            }

            $varPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'var');

            self::$environment = new Environment(
                new FilesystemLoader($varPath . DIRECTORY_SEPARATOR . 'templates')
            );
        }

        // TODO: init functions etc
    }

    /**
     * @return Environment
     * @throws TwigException
     */
    public static function getEnvironment(): Environment
    {
        if(self::$environment === null) {
            self::init();
        }

        return self::$environment;
    }
}