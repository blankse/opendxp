# Event Listener UI

## General

The OpenDXP Backend UI is based upon the [Ext JS](https://www.sencha.com/products/extjs/#overview) Framework. An event listener can
add Ext components to the user interface or execute any other JavaScript required in the listener context.

All JavaScript and CSS which should be included, needs to be defined in your bundle class, as described in 
[OpenDXP Bundles](./README.md). 

Alternatively, you can setup this via an Eventlistener:

```yaml
services:
  # adds additional static files to admin backend
  App\EventListener\OpenDxpAdminListener:
    tags:
      - { name: kernel.event_listener, event: opendxp.bundle_manager.paths.css, method: addCSSFiles }
      - { name: kernel.event_listener, event: opendxp.bundle_manager.paths.js, method: addJSFiles }
```

```php
<?php
namespace App\EventListener;

use OpenDxp\Event\BundleManager\PathsEvent;

class OpenDxpAdminListener
{
    public function addCSSFiles(PathsEvent $event): void
    {
        $event->addPaths([
            '/admin-static/css/admin-style.css',
        ]);
    }

    public function addJSFiles(PathsEvent $event): void
    {
        $event->addPaths([
            '/admin-static/js/startup.js',
        ]);
    }
}
```


These scripts are loaded last upon OpenDXP startup. They are loaded in the same order as specified in the bundle class.

Starting point for javascript development is the javascript event listener.

A listener can look as follows: 
```javascript
document.addEventListener(opendxp.events.opendxpReady, (e) => {
    //print out the parameters of the event
    console.log(e.detail)
});
```

## JavaScript UI Events

For registering events just add a listener with some of the events from [events.js](https://github.com/open-dxp/admin-ui-classic-bundle/blob/1.x/public/js/opendxp/events.js). 


## Validate OpenDXP Object's Data in frontend before saving

It is possible to validate OpenDXP Object's Data in frontend and cancel the saving if needed.

This can be done by using preventDefault() and stopPropagation():

Code example in startup.js:

```javascript
document.addEventListener(opendxp.events.preSaveObject, (e) => {
    let userAnswer = confirm(`Are you sure you want to save ${e.detail.object.data.general.className}?`);
    if (!userAnswer) {
        e.preventDefault();
        e.stopPropagation();
        opendxp.helpers.showNotification(t("Info"), t("saving_failed") + ' ' + 'placeholder', 'info');

    }
});
```

## I18n texts for js

OpenDXP supports i18n for UI extensions. First see the [i18n section for bundles](./README.md) how to prepare the data 
server-side. 

Once this is done, translations can be accessed anywhere in the javascript code by calling

```javascript
t('translation_key')
```

## Adding Custom Main Navigation Items

It is possible to add leftside main navigation via event listener and the `preMenuBuild` event. See the following example to know how: 

```javascript
opendxp.plugin.mybundle = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
    },

    preMenuBuild: function (e) {
        // the event contains the existing menu
        let menu = e.detail.menu;

        let items = [];
        // the property name is used as id with the prefix opendxp_menu_ in the html markup e.g. opendxp_menu_mybundle
        menu.mybundle = {
            label: t('myBundleLabel'), // set your label here, will be shown as tooltip
            iconCls: 'opendxp_main_nav_icon_myIcon', // set full icon name here
            priority: 42, // define the position where you menu should be shown. Core menu items will leave a gap of 10 custom main menu items
            items: items, //if your main menu has subitems please see Adding Custom Submenus To ExistingNavigation Items 
            shadow: false,
            handler: this.openMyBundle, // defining a handler will override the standard "showSubMenu" functionality, use in combination with "noSubmenus"
            noSubmenus: true, // if there are no submenus set to true otherwise menu won't show up
            cls: "opendxp_navigation_flyout", // use OpenDxp_navigation_flyout if you have subitems
        };
    },

    openMyBundle: function(e) {
        try {
            opendxp.globalmanager.get("plugin_opendxp_mybundle").activate();
        } catch (e) {
            opendxp.globalmanager.add("plugin_opendxp_mybundle", new opendxp.plugin.mybundle());
        }
    }
});

var myBundle = new opendxp.plugin.mybundle();
```
## Adding Custom Submenus To ExistingNavigation Items

It is possible to add submenus to existing menus just by pushing a new menu item into the submenu.

```javascript
opendxp.registerNS("opendxp.bundle.glossary.startup");

opendxp.bundle.glossary.startup = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
    },

    preMenuBuild: function (e) {
        let menu = e.detail.menu;
        // get the user to check for permissions
        const user = opendxp.globalmanager.get('user');
        const perspectiveCfg = opendxp.globalmanager.get("perspective");

        if (menu.extras && user.isAllowed("glossary") && perspectiveCfg.inToolbar("extras.glossary")) {
            // simply push the new menu item in a existing menu
            menu.extras.items.push({
                text: t("glossary"),
                iconCls: "opendxp_nav_icon_glossary", // make sure your icon class exists
                priority: 5, // define the position where you menu should be shown. Core menu items will leave a gap of 10 custom menu items
                itemId: 'opendxp_menu_extras_glossary', // specify your custom itemId here
                handler: this.editGlossary, // define a handler what should happen if you click on the menu item
            });
        }
    },

    editGlossary: function() {
        try {
            opendxp.globalmanager.get("bundle_glossary").activate();
        } catch (e) {
            opendxp.globalmanager.add("bundle_glossary", new opendxp.bundle.glossary.settings());
        }
    }
});

const opendxpBundleGlossary = new opendxp.bundle.glossary.startup();
```


## Adding Custom Key Bindings

It is possible to add custom key bindings via event listener the `preRegisterKeyBindings` event and a config setting. Most key bindings are used to have shortcuts for menus.

```yaml
opendxp_admin:
    user:
        default_key_bindings:
            glossary:
                key: 'G'
                action: glossary # make sure that the action has the same name as the function you add to the keyBindingMapping e.g. opendxp.helpers.keyBindingMapping.glossary
                alt: true
                shift: true
```

```javascript
opendxp.registerNS("opendxp.bundle.glossary.startup");

opendxp.bundle.glossary.startup = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.preRegisterKeyBindings, this.registerKeyBinding.bind(this));
    },
    
    registerKeyBinding: function(e) {
        const user = opendxp.globalmanager.get('user');
        // always check for permissions before adding the key binding
        if (user.isAllowed("glossary")) {
            // make sure the function and the action name are the same
            opendxp.helpers.keyBindingMapping.glossary = function() {
                opendxpBundleGlossary.editGlossary();
            }
        }
    }
});

const opendxpBundleGlossary = new opendxp.bundle.glossary.startup();
```
