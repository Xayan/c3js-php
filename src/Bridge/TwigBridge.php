<?php

namespace C3\Bridge;

use C3\Exception\TwigException;
use ReflectionClass;
use ReflectionObject;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use Twig\TwigTest;

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
     * @param Environment|null $environment
     * @return void
     * @throws TwigException
     */
    public static function init(?Environment $environment = null): void
    {
        if(!self::exists()) {
            throw new TwigException("Twig not found");
        }

        $varPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'var');

        self::$environment = new Environment(
            new FilesystemLoader($varPath . DIRECTORY_SEPARATOR . 'templates')
        );

        self::registerExtensions();
    }

    public static function setExternalEnvironment(Environment $environment)
    {
        self::$environment = $environment;
        self::registerExtensions();
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

    /**
     * @return void
     */
    private static function registerExtensions(): void
    {
        self::$environment->addFunction(new TwigFunction('_css', function(string $selector, $style) {
            if(is_array($style)) {
                $styles = [];

                foreach($style as $key => $value) {
                    if(is_numeric($key)) {
                        $styles[] = $value;
                    } else {
                        $styles[] = sprintf("%s: %s", $key, $value);
                    }
                }

                $styleString = implode('; ', $styles);
            } else {
                $styleString = $style;
            }

            return sprintf("%s { %s }", $selector, $styleString);
        }));

        self::$environment->addTest(
            new TwigTest('_modified', function($value, $class, string $fieldName) {
                if(is_object($class)) {
                    $reflection = new ReflectionObject($class);
                } else {
                    $reflection = new ReflectionClass($class);
                }

                $properties = $reflection->getDefaultProperties();

                if(!isset($properties[$fieldName])) {
                    return false;
                }

                return $properties[$fieldName] !== $value;
            })
        );
    }
}