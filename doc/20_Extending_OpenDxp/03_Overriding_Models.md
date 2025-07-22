# Overriding Models / Entities in OpenDXP
 
Sometimes it is necessary to override certain functionalities of OpenDXP's core models, therefore it is possible to 
override the core models with your own classes. 

Currently this works for all implementations of the following classes (but not for these classes directly): 
- `OpenDxp\Model\Document`
- `OpenDxp\Model\Document\Listing`
- `OpenDxp\Model\AbstractObject`
- `OpenDxp\Model\DataObject\Listing`
- `OpenDxp\Model\Asset`
- `OpenDxp\Model\Asset\Listing` 

So for example overriding a listing class of a custom class definition like `OpenDxp\Model\DataObject\News\Listing` or 
`OpenDxp\Model\Asset\Image` is supported. 

But you cannot override `OpenDxp\Model\Asset` (or the other abstract model classes) ifself (as it is the parent class of for example `OpenDxp\Model\Asset\Image` and that would mean to change the class hierarchy). 

## Configure an Override 

The configuration is a simple key / value map in your `config/config.yaml` using the key 
`opendxp.models.class_overrides`, for example: 

```yaml
opendxp:
    models:
        class_overrides:
            'OpenDxp\Model\DataObject\News': 'App\Model\DataObject\News'
            'OpenDxp\Model\DataObject\News\Listing': 'App\Model\DataObject\News\Listing'
```

**It is crucial that your override class extends the origin class, if not you'll break the entire system.**

> **Don't forget to clear all caches (Symfony + Data Cache) after you have configured a class override**
`./bin/console cache:clear --no-warmup && ./bin/console opendxp:cache:clear`

## Example 

In your `config/config.yaml`: 

```yaml
opendxp:
    models:
        class_overrides:
            'OpenDxp\Model\DataObject\News': 'App\Model\DataObject\News'
```

Your `App\Model\DataObject\News`: 

```php
<?php 

namespace App\Model\DataObject; 

class News extends \OpenDxp\Model\DataObject\News
{
    // start overriding stuff 
    public function getMyCustomAttribute(): mixed
    {
        ...
    }
}
```
