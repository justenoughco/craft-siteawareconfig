# Multi Site Config plugin for Craft CMS 3.x

## What?

Set & retrieve Craft CMS config file values that are site-aware, with a clean API and easy defaults.

## Why?

When working with [multi-site Craft CMS installs](https://craftcms.com/docs/3.x/sites.html#creating-a-site), you may find yourself needing config values than should exist for all sites in an install, but vary in value between the sites themselves.

This plugin allows you to share your template code / partials across sites, while always getting the correct value for the current site (or for a different site, if required).

Example usecases:

- API keys
- GA / GTM IDs
- Social network links
- Asset paths (logos etc)

### Why use this instead of

* **Logic in my templates?** That works, but it's ugly as sin, and quickly gets repetitive. As much as possible, templates should just format and display data that is passed from PHP
* **A global?** You could, but this is much faster (no DB / fields overhead), more lightweight and allows for both defaults and sharing values between sites / groups of sites if required 
* **Environment variables?** You should use Environment Variables for config whenever you can! But with multi-site installs they're often not enough on their own. You can (and should) use `App::env()` inside your `config/siteware.php` to set values that belong in the environment (like API keys) that you may need to maintain per-site.
* **ustom key in `config/general.php`?** For basic use cases, this works fine, but again setting defaults / fallback values gets complex quickly.


## How?

This plugin uses Craft's built-in [`ConfigHelper::localizedValue()`](https://docs.craftcms.com/api/v3/craft-helpers-confighelper.html#method-localizedvalue) helper to parse values in a `config/siteaware.php` config file and make them site aware. You can then consume them in your template / PHP code without having to worry about getting the right value for the right site.

See [configuration](#configuration) below for config file syntax.


### Requirements

This plugin requires PHP 7.4+ and Craft CMS 3.5 or later. It will _probably_ work with older versions of Craft, but is untested with them.

### Installation

```bash
cd /path/to/project
composer require justenoughco/craft-siteawareconfig
./craft plugin/install site-aware-config
```

### Configuration

Edit the config file at `config/siteaware.php` to set your config values.

```php
// Craft's multi-environment config syntax is (optionally) supported
    '*' => [
        'staticValue' => 'I am the same for every site',
        'myCustomConfigKey' => [
            // value for the default site
            'default' => 'defaultValue',
            // value for another site
            'aSecondSite' => 'alternateValue',
        ],
        // be careful with nested array values - per site values 
        // are replaced, not merged. e.g. [1] below
        'aDeepNestedKey' => [
            'default' => [
                'apiKey' => 'API_KEY_FOR_DEFAULT_SITE',
                'apiSecret' => 'API_SECRET_FOR_DEFAULT_SITE',
                'deeper' => [
                    'foo' => 'bar',
                    'baz' => 'cux',
                ],
            ],
            // [1] Because we've overwritten aDeepNestedKey for this site,
            // craft.siteAwareConfig.forSite('aSecondSite').aDeepNestedKey.deeper
            // will throw a missing key Error in Twig / UnknownPropertyException
            // in PHP
            'aSecondSite' => [
                'apiKey' => 'API_KEY_FOR_A_SECOND_SITE',
                'apiSecret' => 'API_SECRET_FOR_A_SECOND_SITE',
            ],
        ],
        // Callable example
        'myDynamicKey' => function (string $siteHandle = ''): int {
            return $siteHandle[0] == 'd' ? 1 : 2;
        },
    ],
    // overide a setting in dev environment only
    'dev' => [
        'myCustomConfigKey' => [
            'aSecondSite' => 'aDevOnlyValue',
        ],
        // multi-environment configs are merged, not replaced,
        // so in dev craft.siteAwareConfig.forSite('default').aDeepNestedKey.deeper
        // will work here
        'aDeepNestedKey' => [
            'default' => [
                'apiKey' => 'API_KEY_FOR_DEFAULT_SITE',
            ],
        ],
    ],
 ```

### Retrieving config values

#### Templating

Config values for the current Site are exposed to Twig under `craft.siteAwareConfig.currentSite`:

```twig
{{ craft.siteAwareConfig.currentSite.perSiteConfigKey }}

```

 If needed, you can access the config for any site by passing its handle to `craft.siteAwareConfig.forSite()`

```twig
{% set defaultSiteConfig = craft.siteAwareConfig.forSite('siteHandle') }}

```

#### PHP

An instance of `justenough\siteawareconfig\services\Config` is what gets exposed to Twig. Should you need to, you can also access site aware config settings from PHP-land:

```php
use justenough\siteawareconfig\Plugin as SiteAwareConfigPlugin;

$currentSiteConfig = SiteAwareConfigPlugin::getInstance()->config->config;
$otherSiteConfig = SiteAwareConfigPlugin::getInstance()->config->forSite('siteHandle');

```

The returned `SiteAwareConfig` extends Yii's [`BaseObject`](https://www.yiiframework.com/doc/api/2.0/yii-base-baseobject).



### Caveats / Potential "Gotchas"

While values in multi-environment configs are **merged**, site-aware values using the array syntax are **replaced**. This is usually only an issue if you are using an associative array for your config value (for example to group related values together). 

If you need to merge values between sites either flatten your values, or use a callable to handle the merging/default logic.

## Attributions

Brought to you by [Just Enough Consulting](https://justenough.co/)

Icon by [Arthur Shlain via the NounProject.com](https://thenounproject.com/icon/config-900563/)