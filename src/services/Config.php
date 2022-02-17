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

namespace justenough\siteawareconfig\services;

use Craft;
use craft\base\Component;

use justenough\siteawareconfig\config\SiteAwareConfig;
use justenough\siteawareconfig\Plugin;

/**
 * @author    Just Enough Consulting
 * @package   PerSite
 * @since     1.0.0
 */
class Config extends Component
{
    public static array $rawConfig;
    public SiteAwareConfig $currentSite;

    public function init(): void
    {
        self::$rawConfig = $this->getRawConfig();
        $this->currentSite = $this->forSite();

        parent::init();
    }

    public function getRawConfig(): array
    {
        $rawConfig = Craft::$app->getConfig()->getConfigFromFile(Plugin::CONFIG_FILE_SLUG);

        if (! $rawConfig) {
            Craft::warning('No config found in ' . Plugin::getConfigFilePath());
        }

        return $rawConfig;
    }

    public function forSite(?string $siteHandle = null): SiteAwareConfig
    {
        $siteHandle = $siteHandle ?? Craft::$app->getSites()->getCurrentSite()->handle;

        $config = new SiteAwareConfig($siteHandle, self::$rawConfig);

        return $config;
    }
}
