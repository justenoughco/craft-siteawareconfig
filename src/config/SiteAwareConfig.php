<?php

declare(strict_types=1);
/**
 * Multi Site Config plugin for Craft CMS 3.x
 *
 * Store and retrieve config values in a site-aware way
 *
 * @link      https://justenough.co/
 * @copyright Copyright (c) 2022 Just Enough Consulting
 */

namespace justenough\siteawareconfig\config;

use craft\helpers\ConfigHelper;
use yii\base\BaseObject;
use yii\base\UnknownMethodException;
use yii\base\UnknownPropertyException;

class SiteAwareConfig extends BaseObject
{
    public string $siteHandle;
    public array $config;

    public function __construct(string $siteHandle, array $config)
    {
        $this->siteHandle = $siteHandle;
        $this->config = $config;

        parent::__construct($config);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->config)) {
            return ConfigHelper::localizedValue($this->config[$name], $this->siteHandle);
        }

        throw new UnknownPropertyException(
            'Getting unknown property: ' . get_class($this) . '::' . $name
        );
    }

    public function __set($name, $value): void
    {
        if ($this->hasProperty($name)) {
            $this->config[$name] = $value;
        } else {
            throw new UnknownPropertyException(
                'Setting unknown property: ' . get_class($this) . '::' . $name
            );
        }
    }

    /**
     * @inheritdoc
     *
     * Need __call in addition to __get as Twig checks for properties before attempting to
     * retrieve them (which will always fail) rather accessing anyway (which would cause our
     *  __get to be called). Twig then then tries to call the property as a method which will
     *   hit here...
     */
    public function __call($name, $args)
    {
        if ($this->hasProperty($name)) {
            return $this->localized($name);
        } else {
            throw new UnknownMethodException(
                'Calling unknown method: ' . get_class($this) . "::$name()"
            );
        }
    }

    public function hasProperty($name, $checkVars = true)
    {
        return array_key_exists($name, $this->config);
    }

    private function localized(string $name): mixed
    {
        return ConfigHelper::localizedValue($this->config[$name], $this->siteHandle);
    }
}
