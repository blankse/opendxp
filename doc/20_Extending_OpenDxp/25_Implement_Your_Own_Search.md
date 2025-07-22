# Implement Your Own Search

## Register Implementation

OpenDXP provides the `searchImplementationRegistry` (= facade) where you can register your custom implementation.

### Register a custom implementation
```js
opendxp.registerNS('opendxp.bundle.search');

opendxp.bundle.search = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function () {
        this.searchRegistry = opendxp.globalmanager.get('searchImplementationRegistry');
        this.searchRegistry.registerImplementation(new your.custom.search.implementation());
    }
)};
```

### Check for an Implementation

Thanks to the registry we can check if a custom search implementation has been registered.

```js
opendxp.globalmanager.get('searchImplementationRegistry').hasImplementation();

//or a more readable way
opendxp.helpers.hasSearchImplementation()
```

## Create a custom search implementation

If you want to create your own search implementation you have to provide some predefined methods. 
These methods are: `openItemSelector`, `showQuickSearch`, `hideQuickSearch` and `getObjectRelationInlineSearchRoute`.
- The `openItemSelector` method will be triggered by certain data object fields and editables through the 
  [helper.js](https://github.com/open-dxp/admin-ui-classic-bundle/blob/1.x/public/js/opendxp/helpers.js#L822).
- The `showQuickSearch` and `hideQuickSearch` is responsible for managing the quickSearch.
- The `getObjectRelationInlineSearchRoute` has to return the route to `DataObjectController::optionsAction`.

For reference, you can check the implementation in the OpenDxpSimpleBackendSearchBundle.
See [service.js](https://github.com/open-dxp/opendxp/blob/1.x/bundles/SimpleBackendSearchBundle/public/js/opendxp/element/service.js) 
and [selector.js](https://github.com/open-dxp/opendxp/blob/1.x/bundles/SimpleBackendSearchBundle/public/js/opendxp/element/selector/selector.js).

## Using OpenDXP without the SimpleBackendSearchBundle

If you use OpenDxp without the SimpleBackendSearchBundle you have to consider the following drawbacks.

**SearchButton**

OpenDXP will hide all the search buttons from object fields and editables (e.g relations, image, gallery, video, ...).

**Inline Search**

OpenDXP provides the option to add a inline search to some relations. This option won't be there. 

**Toolbar Search**

OpenDXP will also have no search button in the toolbar. According to that the quickSearch will also be gone.

**GDPR Search**

OpenDXP will only have a very basic implementation of the GDPR search.
Especially for searching through data objects it's highly recommended to use the SimpleBackendSearchBundle.
