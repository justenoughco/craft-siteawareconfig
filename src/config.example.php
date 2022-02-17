<?php

declare(strict_types=1);

/**
 * This file will be parsed as a normal Craft Config file *first* which means you
 * can optionally use per-environment configs here
 *
 * Values will then be parsed with ConfigHelper::localizedValue() to
 * make them per-site aware
 *
 * https://docs.craftcms.com/api/v3/craft-helpers-confighelper.html#method-localizedvalue
 *
 * How different forms of value will be handled (lifted direct from the Craft docs):
 *
 * 1. A scalar value or null: represents the desired value directly, and will be returned verbatim.
 *
 * 2. An associative array: represents the desired values across all sites, indexed by site handles.
 *  If a matching site handle isn’t listed, the first value will be returned.
 *
 * 3. A PHP callable: either an anonymous function or an array representing a class method
 * ([$class or $object, $method]). * The callable will be passed the site handle if known,
 * and should return the desired config value.
 *
 */

return [
    // all environments
    // '*' => [
    //     'staticValue' => 'I am the same for every site',
    //     'myCustomConfigKey' => [
    //         // value for the default site
    //         'default' => 'defaultValue',
    //         // value for another site
    //         'aSecondSite' => 'alternateValue',
    //     ],
    //     // be careful with nested array values - per site values
    //     // are replaced, not merged. e.g. [1] below
    //     'aDeepNestedKey' => [
    //         'default' => [
    //             'apiKey' => 'API_KEY_FOR_DEFAULT_SITE',
    //             'apiSecret' => 'API_SECRET_FOR_DEFAULT_SITE',
    //             'deeper' => [
    //                 'foo' => 'bar',
    //                 'baz' => 'cux',
    //             ],
    //         ],
    //         // [1] Because we've overwritten aDeepNestedKey for this site,
    //         // craft.siteAwareConfig.aDeepNestedKey.deeper will
    //         // throw a missing key Error in Twig / UnknownPropertyException
    //         // in PHP. Flatten your data structure or use a callable if
    //         // this is an issue for you
    //         'aSecondSite' => [
    //             'apiKey' => 'API_KEY_FOR_A_SECOND_SITE',
    //             'apiSecret' => 'API_SECRET_FOR_A_SECOND_SITE',
    //         ],
    //     ],
    //     // Callable example
    //     'myDynamicKey' => function (string $siteHandle = ''): int {
    //         return $siteHandle[0] == 'd' ? 1 : 2;
    //     },
    // ],
    // // overide a setting in dev environment only
    // 'dev' => [
    //     'myCustomConfigKey' => [
    //         'aSecondSite' => 'aDevOnlyValue',
    //     ],
    //     // multi-environment configs are merged, not replaced,
    //     // so in dev craft.siteAwareConfig.forSite('default').aDeepNestedKey.deeper
    //     // will work here
    //     'aDeepNestedKey' => [
    //         'default' => [
    //             'apiKey' => 'API_KEY_FOR_DEFAULT_SITE',
    //         ],
    //     ],
    // ],
];
