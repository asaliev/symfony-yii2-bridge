# symfony-yii2-bridge

This bundle was created as part of a workshop presentation for my team. The goal was to tackle a hypothetical scenario where we needed to migrate a legacy Yii2 application to Symfony incrementally. It is not intended to be a production-ready plugin but serves as an example of how such a migration could be approached.

Supports PHP 7.4+ and Symfony 5.4+.

## Overview

 - Registers Yii2 routes with Symfony routing component.
 - Dispatches Yii2 routes to Yii2 Application.
 - Yii2 Application is executed via a message bus, keeping the Yii2 application lifecycle intact.

## Installation

1. Install the package via Composer:

```bash
composer require asaliev/symfony-yii2-bridge
```

2. Register the bundle in your Symfony application:

```php
// config/bundles.php
return [
    // Other bundles...
    Asaliev\Yii2Bridge\Yii2Bundle::class => ['all' => true],
];
```

3. Register the yii2 route type in your Symfony application:

```yaml
# config/routes.yaml
yii2_routes:
  resource: .
  type: yii2_routes
```

4. Configure the bundle in your Symfony application:

```yaml
# config/packages/yii2.yaml
web_config_path: '%kernel.project_dir%/path/to/web.php'
messenger_bus: 'yii.app.bus'

# Optionally override the default Yii2 container class with an adapter which checks whether a service is registered in the Symfony container first.
# Otherwise you may provide your own implementation of `yii\di\Container`.

#override_yii_container_class: 'Asaliev\Yii2Bridge\Application\PsrPreferredContainerAdapter'
```

5. Configure the Yii2 message bus in your Symfony application:

```yaml
# config/packages/messenger.yaml
framework:
    messenger:
        buses:
            # ...Other configs
            yii.app.bus:
                middleware:
                    - App\Middleware\SomeExtraMiddleware
```

6. In the Yii2 configuration file, update the Request class to use `Asaliev\Yii2Bridge\Http\HeaderlessResponse` instead of the default `yii\web\Request`. This is necessary because Yii2 will output headers and cookies before we have a chance to halt it's output.

```php
# path/to/yii2/web.php
return [
    // ...
    'components' => [
        // ...
        'response' => [
            // ...
            'class' => HeaderlessResponse::class,
        ],
];
```

## License
This project is licensed under the MIT License. See the LICENSE file for details.
