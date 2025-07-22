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

opendxp.registerNS('opendxp.bundle.search.layout.toolbar');

/**
 * @private
 */
opendxp.bundle.search.layout.toolbar = Class.create({
    initialize: function (menu) {
        this.perspectiveCfg = opendxp.globalmanager.get('perspective');
        this.user = opendxp.globalmanager.get('user');
        this.searchRegistry = opendxp.globalmanager.get('searchImplementationRegistry');
        this.menu = menu;

        this.createSearchEntry();
    },

    createSearchEntry: function () {
        if (this.perspectiveCfg.inToolbar("search")) {
            const searchItems = [];

            if ((this.user.isAllowed("documents") ||
                this.user.isAllowed("assets") ||
                this.user.isAllowed("objects")) &&
                this.perspectiveCfg.inToolbar("search.quickSearch")) {
                searchItems.push({
                    text: t("quicksearch"),
                    iconCls: "opendxp_nav_icon_quicksearch",
                    itemId: 'opendxp_menu_search_quick_search',
                    handler: function () {
                        this.searchRegistry.showQuickSearch();
                    }.bind(this)
                });
                searchItems.push('-');
            }

            const searchAction = function (type) {
                opendxp.globalmanager.get('searchImplementationRegistry').openItemSelector(
                    false,
                    function (selection) {
                        opendxp.helpers.openElement(selection.id, selection.type, selection.subtype);
                    },
                    {type: [type]},
                    {
                        asTab: true,
                        context: {
                            scope: "globalSearch"
                        }
                    }
                );
            };

            if (this.user.isAllowed("documents") && this.perspectiveCfg.inToolbar("search.documents")) {
                searchItems.push({
                    text: t("documents"),
                    iconCls: "opendxp_nav_icon_document",
                    itemId: 'opendxp_menu_search_documents',
                    handler: searchAction.bind(this, "document")
                });
            }

            if (this.user.isAllowed("assets") && this.perspectiveCfg.inToolbar("search.assets")) {
                searchItems.push({
                    text: t("assets"),
                    iconCls: "opendxp_nav_icon_asset",
                    itemId: 'opendxp_menu_search_assets',
                    handler: searchAction.bind(this, "asset")
                });
            }

            if (this.user.isAllowed("objects") && this.perspectiveCfg.inToolbar("search.objects")) {
                searchItems.push({
                    text: t("data_objects"),
                    iconCls: "opendxp_nav_icon_object",
                    itemId: 'opendxp_menu_search_data_objects',
                    handler: searchAction.bind(this, "object")
                });
            }

            if (searchItems.length > 0) {
                this.menu.search = {
                    label: t('search'),
                    iconCls: 'opendxp_main_nav_icon_search',
                    items: searchItems,
                    shadow: false,
                    cls: "opendxp_navigation_flyout"
                };
            }
        }
    }
});