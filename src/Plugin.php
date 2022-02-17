<?php

/**
 * Multi Site Config plugin for Craft CMS 3.x
 *
 * Store config values in a site-aware way
 *
 * @link      https://justenough.co/
 * @copyright Copyright (c) 2022 Just Enough Consulting
 */
declare(strict_types=1);

namespace justenough\siteawareconfig;

use Craft;

use craft\base\Plugin as BasePlugin;
use craft\web\twig\variables\CraftVariable;
use justenough\siteawareconfig\services\Config as ConfigService;

use yii\base\Event;

/**
 * Class Plugin
 *
 * @author    Just Enough Consulting
 * @package   PerSite
 * @since     1.0.0
 *
 * @property  ConfigService $config
 */

class Plugin extends BasePlugin
{
    /**
     * @var Plugin
     */
    public static $plugin;


    public $hasCpSettings = false;
    public $hasCpSection = false;

    public const CONFIG_FILE_SLUG = 'siteaware';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'config' => ConfigService::class,
        ]);

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('siteAwareConfig', $this->config);
            }
        );

        Craft::info($this->name . " plugin loaded", __METHOD__);
    }

    public static function getConfigFilePath(): string
    {
        return Craft::$app->getConfig()->configDir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_SLUG . '.php';
    }

    /**
     * Copy example config to project's config folder
     */
    protected function afterInstall(): void
    {
        $configSource = __DIR__ . DIRECTORY_SEPARATOR . 'config.example.php';
        $configTarget = self::getConfigFilePath();

        if (! file_exists($configTarget)) {
            copy($configSource, $configTarget);
        }
    }
}
