# OpenDXP Templates

## Introduction

In general the templates are located in: `templates/[controller]/[action].html.twig` 
but [Symfony-style locations](https://symfony.com/doc/4.4/best_practices.html#use-the-default-directory-structure) also work (both controller as well as action without their suffix).  

OpenDXP uses the Twig templating engine, you can use Twig exactly as documented in:

* [Twig Documentation](https://twig.symfony.com/doc/3.x/)
* [Symfony Templating Documentation](https://symfony.com/doc/current/templating.html)

Just use attributes or render the view directly to use Twig:

```php
<?php

namespace App\Controller;

use OpenDxp\Controller\FrontendController;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;

class MyController extends FrontendController
{
    /**
     * The attribute will resolve the defined view
     */
    #[Template('content/default.html.twig', vars: ['param1' => 'value1'])]
    public function attributeAction(): void
    {   
    }
    
    public function directRenderAction(): Response
    {
        return $this->render('my/custom/action.html.twig', ['param1' => 'value1']);
    }
}
```

Of course, you can just set a custom template for OpenDXP documents in the admin interface and that
template will be used for auto-rendering when the controller does not return a response.


## Twig Reference

To make OpenDXP's functions available in Twig templates, OpenDXP implements a set of extensions. 

You can take a look at the [implementations](https://github.com/open-dxp/opendxp/tree/11.x/lib/Twig)
for further details. Note that all of OpenDXP's Twig extensions are prefixed with `pimcore` to avoid naming collisions.

### OpenDXP Editables

```twig
<h1>{{ opendxp_input('headline') }}</h1>

{{ opendxp_wysiwyg('content') }}

{{ opendxp_select('type', { reload: true, store: [["video","video"], ["image","image"]] }) }}
```

Please note that if you store the editable in a variable, you'll need to pipe it through the raw filter on output if it
generates HTML as otherwise the HTML will be escaped by twig.

```twig
{% set content = opendxp_wysiwyg('content') %}

{# this will be escaped HTML #}
{{ content }}

{# HTML will be rendered #}
{{ content|raw }}
```

### Functions

#### Loading Objects

The following functions can be used to load OpenDXP elements from within a template:

* `opendxp_document`
* `opendxp_document_by_path`
* `opendxp_site`
* `opendxp_asset`
* `opendxp_asset_by_path`
* `opendxp_object`
* `opendxp_object_by_path`

```twig
{% set myObject = opendxp_object(123) %}
{{ myObject.getTitle() }}
```
or
```twig
{% set myObject = opendxp_object_by_path("/path/to/my/object") %}
{{ myObject.title }}
```

For documents, OpenDXP also provides a function to handle hardlinks through the `opendxp_document_wrap_hardlink` method.

See [OpenDxpObjectExtension](https://github.com/open-dxp/opendxp/blob/1.x/lib/Twig/Extension/OpenDxpObjectExtension.php)
for details.


#### Subrequests

```twig
{# include another document #}
{{ opendxp_inc('/snippets/foo') }}
```

See [Template Extensions](./02_Template_Extensions/README.md) for details.


#### Templating Extensions

The following extensions can directly be used on Twig. See [Template Extensions](./02_Template_Extensions/README.md) for a 
detailed description of every helper:

**Functions:**
* `opendxp_head_link`
* `opendxp_head_meta`
* `opendxp_head_script`
* `opendxp_head_style`
* `opendxp_head_title`
* `opendxp_inline_script`
* `opendxp_placeholder`
* `opendxp_url`
* `opendxp_cache` (deprecated)

**Tags:**
* `opendxpcache`

#### Block elements

As Twig does not provide a `while` control structure which is needed to iterate a [Block](../../03_Documents/01_Editables/06_Block.md)
editable, we introduced a function called `opendxp_iterate_block` to allow walking through every block element:

```twig
{% opendxpblock "contentblock" %}
    <h2>{{ opendxp_input("subline") }}</h2>
    {{ opendxp_wysiwyg("content") }}
{% endopendxpblock %}
```

### Tests

#### `instanceof`

Can be used to test if an object is an instance of a given class.

```twig
{% if image is instanceof('\\OpenDxp\\Model\\Asset\\Image') %}
    {# ... #}
{% endif %}
```

####  OpenDXP Specialities

OpenDXP provides a few special functionalities to make templates even more powerful. 
These are explained in following sub chapters:

* [Template inheritance and Layouts](./01_Layouts.md) - Use layouts and template inheritance to define everything that repeats on a page. 
* [Template Extensions](./02_Template_Extensions/README.md) - Use twig extensions for things like includes, translations, cache, glossary, etc.
* [Thumbnails](./04_Thumbnails.md) - Learn how to include images into templates with using Thumbnails.
