# Bake

## Install
Install the plugin with composer from your CakePHP project's ROOT directory (where composer.json file is located)
```bash
php composer.phar require burzum/cakephp-service-layer
```
or 
```bash
composer require burzum/cakephp-service-layer
```

## Setup
First, make sure the plugin is loaded.

Inside your Application.php:
```php
$this->addPlugin('Burzum/CakeServiceLayer');
```

## Bake your Service

Once the plugin is loaded, you can bake your service using:
```
bin/cake bake service MyFooBar
```
This would create a service class `src/Service/MyFooBarService.php`.

You can also bake inside sub namespaces (subfolders):
```
bin/cake bake service My/Foo/Bar
```
This would create a service class `src/Service/My/Foo/BarService.php`.
