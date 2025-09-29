# Deployment Tools

Following tools are provided by OpenDXP to support deployment processes.

## OpenDXP Configurations

All OpenDXP configurations are saved as YAML or PHP files on the file system. As a result they can be included into
[version control systems](./01_Version_Control_Systems.md) and by utilizing the
[multi environment feature](03_Configuration_Environments.md) different configuration files for different deployment stages
can be defined.

## OpenDXP Class Definitions

As with OpenDXP configurations also OpenDXP class definitions are saved as PHP configuration files and therefore can
be added to version control systems and be deployed to different deployment stages.

The PHP configuration files and PHP classes will be written to the `var/classes` directory by default.
To disallow modification and turn a class to be read-only, you can create a copy
at `config/opendxp/classes`.

Regarding the class modification, there is also an optional env variable `OPENDXP_CLASS_DEFINITION_WRITABLE` that can be considered and set.

- `0` To disallow completely write access, including the creation of new classes.
- `1` To allow the modification, including the classes in `config/opendxp/classes` that normally are read-only.
- when `not set` classes in `config/opendxp/classes` are read-only, but new classes are allowed and will be created in `var/classes`. 

With the env variable `OPENDXP_CLASS_DEFINITION_DIRECTORY` you can specify the directory to search for your class definitions
if you do not want opendxp to search in `var/classes` or `config/opendxp/classes`.

> **Note**: Changes on OpenDXP class definitions not only have influence to configuration files but also on the database.
> If deploying changes between different deployment stages also database changes need to be deployed. This can be done
> with the `opendxp:deployment:classes-rebuild` command.


After every code update you should use the `opendxp:deployment:classes-rebuild` command to push changes to the database.

```bash
./bin/console opendxp:deployment:classes-rebuild
```

To create new classes from your configuration files in the database you can use the `create-classes` option. 

```bash
./bin/console opendxp:deployment:classes-rebuild --create-classes
```

If you use [Composer's autoloader optimization](https://getcomposer.org/doc/articles/autoloader-optimization.md), you have to register the newly created classes via:
```bash
composer dump-autoload --optimize
```

As an alternative also class export to json-files and the class import commands can be used.

```bash
./bin/console opendxp:definition:import:objectbrick /brick_jsonfile_path.json

./bin/console opendxp:definition:import:fieldcollection /collection_jsonfile_path.json

./bin/console opendxp:definition:import:class /class_jsonfile_path.json
```


## OpenDXP Console

The [OpenDXP Console](../19_Development_Tools_and_Details/11_Console_CLI.md) provides several useful tasks for deployment.
 These tasks can be integrated into custom deployment workflows and tools. One example for them would be the OpenDXP
 class definitions as described above.

To get a list of all available commands use `./bin/console list`.

#### Potentially useful commands:

| Command                                   | Description                                                                                                                       |
|-------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------|
| opendxp:mysql-tools                       | Optimize and warm up mysql database                                                                                               |
| opendxp:search-backend-reindex            | Re-indexes the backend search of OpenDXP (only available if you have installed the simpleBackendSearchBundle)                     |
| opendxp:cache:clear                       | Clear OpenDXP core caches                                                                                                         |
| cache:clear                               | Clear Symfony caches                                                                                                              |
| opendxp:cache:warming                     | Warm up caches                                                                                                                    |
| opendxp:classificationstore:delete-store  | Delete Classification Store                                                                                                       |
| opendxp:definition:import:class           | Import Class definition from a JSON export                                                                                        |
| opendxp:definition:import:customlayout    | Import Customlayout definition from a JSON export                                                                                 |
| opendxp:definition:import:fieldcollection | Import FieldCollection definition from a JSON export                                                                              |
| opendxp:definition:import:objectbrick     | Import ObjectBrick definition from a JSON export                                                                                  |
| opendxp:definition:import:units           | Import Quantity value units definition from a JSON export                                                                         |
| opendxp:deployment:classes-rebuild        | Rebuilds classes and db structure based on updated `var/classes/definition_*.php` files                                           |
| opendxp:thumbnails:image                  | Generate image thumbnails, useful to pre-generate thumbnails in the background. Use `--processes` option for parallel processing. |
| opendxp:thumbnails:optimize-images        | Optimize file size of all images in `public/var/tmp`                                                                              |
| opendxp:thumbnails:video                  | Generate video thumbnails, useful to pre-generate thumbnails in the background. Use `--processes` option for parallel processing. |

Find more about the OpenDXP Console on the [dedicated page](../19_Development_Tools_and_Details/11_Console_CLI.md).


## Content migration

The content migration between environments is not provided by OpenDXP and it's not recommended at all.

The content should be created by editors in the production environment and visibility on the frontend can be managed
by built-in features like publishing / unpublishing / [versioning](../18_Tools_and_Features/01_Versioning.md) /
[scheduling](../18_Tools_and_Features/03_Scheduling.md) / preview the effect in editmode.

Therefore, editors shouldn't work on different stages.

Of course, the content migration is possible but this is always a very individual task depending on data model, environments
and use cases.

If you need some kind of content migration utilize the PHP API for [assets](../04_Assets/01_Working_with_PHP_API.md),
[objects](../05_Objects/03_Working_with_PHP_API.md) and [documents](../03_Documents/09_Working_with_PHP_API.md) for doing so.
