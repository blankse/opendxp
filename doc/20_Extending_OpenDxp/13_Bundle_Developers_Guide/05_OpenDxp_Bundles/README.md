# OpenDxp Bundles

OpenDxp bundles follow the same rules as normal bundles, but need to implement [OpenDxp\Extension\Bundle\OpenDxpBundleInterface](https://github.com/open-dxp/opendxp/blob/1.x/lib/Extension/Bundle/OpenDxpBundleInterface.php)
in order to show up in the `opendxp:bundle:list` command. This gives you the following possibilities:

* The bundle shows up in the `opendxp:bundle:list` command with info, if bundle can be installed or uninstalled.
* The bundle can be installed with `opendxp:bundle:install` command or uninstall with `opendxp:bundle:uninstall`
  command to trigger the installation/uninstallation, for example to install/update database structure.
* The bundle adds methods to natively register JS and CSS files to be loaded with the admin interface and in editmode.

To get started quickly, you can extend `OpenDxp\Extension\Bundle\AbstractOpenDxpBundle` which already implements all methods defined by the interface.

If you need to load assets (JS or CSS) in the Admin or Editmode UI please have a look at the [loading assets in the Admin UI](../13_Loading_Admin_UI_Assets.md) section in the docs.

## Installer

By default, a OpenDxp bundle does not define any installation or update routines, but you can use the `getInstaller()` method
to return an instance of a `OpenDxp\Extension\Bundle\Installer\InstallerInterface`. If a bundle returns an installer instance,
this installer will be used by the command `opendxp:bundle:install` to allow installation/uninstallation.

The `install` method can be used to create database tables and do other initial tasks. The `uninstall` method should make
sure to undo all these things. The installer is also the right place to check for requirements such as minimum OpenDxp
version or read/write permissions on the filesystem.

Read more in [Installers](./01_Installers.md).

### Composer bundles

If you provide your bundle via composer, it won't be automatically found. To include your package directory to the list
of scanned paths, please set the package type of your package to `opendxp-bundle`. Additionally, if you set the specific
bundle name through the `opendxp.bundles` composer extra config no filesystem scanning will be done which will have a
positive effect on the bundle lookup performance.

> Whenever you can, you should explicitly set the bundle class name through the extra config.

An example of a `composer.json` defining a OpenDxp bundle:

```json
{
    "name": "myVendor/myBundleName",
    "type": "opendxp-bundle",
    "autoload": {
        "psr-4": {
            "MyBundleName\\": ""
        }
    },
    "extra": {
        "opendxp": {
            "bundles": [
                "MyBundleName\\MyBundleName"
            ]
        }
    }
}
```

#### Returning the composer package version

OpenDxp provides a `OpenDxp\Extension\Bundle\Traits\PackageVersionTrait` which you can include in your bundle. The trait
includes a `getComposerPackageName` method which will return the name defined in your `composer.json` file.

If you want to change the default behavior, all you need to do is to override the `getComposerPackageName` method returning
the name of your composer package (e.g. `company/foo-bundle`):

```php
<?php

declare(strict_types=1);

namespace Company\FooBundle;

use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;
use OpenDxp\Extension\Bundle\Traits\PackageVersionTrait;

class FooBundle extends AbstractOpenDxpBundle
{
    use PackageVersionTrait;

    protected function getComposerPackageName(): string
    {
        // getVersion() will use this name to read the version from
        // PackageVersions and return a normalized value
        return 'company/foo-bundle';
    }
}
```
