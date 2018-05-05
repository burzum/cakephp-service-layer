# IDE support
  
With [IdeHelper](https://github.com/dereuromark/cakephp-ide-helper/) plugin you can get typehinting and autocomplete for your `loadModel()` calls.
Especially if you use PHPStorm, this will make it possible to get support here.

Include that plugin, set up your generator config and run e.g. `bin/cake phpstorm generate`.

You can include the Service generator task in your `config/app.php` on project level:

```php
use Burzum\Cake\Generator\Task\ServiceTask;

return [
  ...
  'IdeHelper' => [
      'generatorTasks' => [
          ServiceTask::class
      ],
  ],
];
```
