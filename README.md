# PrestaSDK

PrestaSDK is a simple and extendable library for developing PrestaShop modules.

## Installation
Use Composer to add PrestaSDK to your PrestaShop module:

```bash
composer require prestaware/prestasdk
```

## Features
- Base `PrestaSDKModule` for uniform module structure
- `PrestaSDKFactory` for loading installers, controllers and utilities
- Utilities for configuration, asset publishing and admin panels

## Usage
Extend `PrestaSDKModule` in your module and define its settings inside `initModule`.

```php
<?php
use PrestaSDK\V040\PrestaSDKModule;

class MyModule extends PrestaSDKModule
{
    public function initModule()
    {
        $this->name = 'my_module';
        $this->version = '1.0.0';
    }
}
```

For a full walkthrough see [docs/intro.md](docs/intro.md) and the example in [`examples/module_integration.php`](examples/module_integration.php).

