# Add Your Own Dependencies and Packages

OpenDXP manages itself all dependencies using composer and therefore you can add your own dependencies by using 
 standard composer functionalities. 

## Basic Example
Use composer in your project root directory, eg. 
```bash
composer require mtdowling/cron-expression
```

## Third party bundles

You can install third party bundles via composer as shown above (as OpenDXP is a standard Symfony application, you should
be able to use any third-party Symfony bundle).

To load a bundle with the application, it must first be enabled in `config/bundles.php` (see [bundles documentation](https://symfony.com/doc/current/bundles.html)).
As OpenDXP defines a `registerBundles` method base Kernel class, which is returns a list of default bundles and priority can be important for config auto loading, the
OpenDXP Kernel exposes a `registerBundlesToCollection`  method which allows to add bundles to a `BundleCollection` with
an optional priority (higher priority is loaded first) and a list of environments to handle (e.g. load only in `dev`
environment).

> Bundles without a priority are registered with a default priority of 0. You can set a negative value if you need to set
  a priority lower than default.

As an example, register a third party bundle on the collection:

```php
<?php

namespace App;

use OpenDxp\HttpKernel\BundleCollection\BundleCollection;
use OpenDxp\Kernel as OpenDxpKernel;

class Kernel extends OpenDxpKernel
{
    /**
     * Adds bundles to register to the bundle collection. The collection is able
     * to handle priorities and environment specific bundles.
     */
    public function registerBundlesToCollection(BundleCollection $collection): void
    {
        if (class_exists('\\XYZBundle\\XYZBundle')) {
            $collection->addBundle(new \XYZBundle\XYZBundle);
        }

        // add a custom third-party bundle here with a high priority and only for dev environment
        $collection->addBundle(new Third\Party\PartyBundle, 10, ['dev']);
    }
}
```

You can read more about the bundle collection and handling of dependencies in [Bundle Collection](./13_Bundle_Developers_Guide/04_Bundle_Collection.md).

### OpenDXP Bundles

For more information see [OpenDXP Bundles](./13_Bundle_Developers_Guide).

Just like third party bundles, OpenDXP bundles can be registered on the kernel by enabling them in the `config/bundles.php`
or manually by adding them via code as shown above. You can use `opendxp:bundle:*` commands
to list or interact with the bundle installer (install/uninstall/update).
