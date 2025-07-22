/**
 * OpenDXP
 *
 * This source file is licensed under the GNU General Public License version 3 (GPLv3).
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (https://pimcore.com)
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.ch)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

opendxp.registerNS('opendxp.bundle.search');

/**
 * @private
 */
opendxp.bundle.search = Class.create({
    registry: null,

    initialize: function () {
        document.addEventListener(opendxp.events.preRegisterKeyBindings, this.registerKeyBinding.bind(this));
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
        document.addEventListener(opendxp.events.openDxpReady, this.openDxpReady.bind(this));
    },

    openDxpReady: function () {
        this.registerSearchService();
    },

    registerKeyBinding: function () {
        opendxp.helpers.keyBindingMapping.quickSearch = function () {
            opendxp.globalmanager.get('searchImplementationRegistry').showQuickSearch();
        }
    },

    registerSearchService: function () {
        this.searchRegistry = opendxp.globalmanager.get('searchImplementationRegistry');

        //register search/selector
        this.searchRegistry.registerImplementation(new opendxp.bundle.search.element.service());
    },

    preMenuBuild: function (event) {
        new opendxp.bundle.search.layout.toolbar(event.detail.menu); //TODO: check if that works
    }
});

const searchBundle = new opendxp.bundle.search();