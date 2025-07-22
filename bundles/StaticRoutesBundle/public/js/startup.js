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

opendxp.registerNS("opendxp.bundle.staticroutes.startup");
/**
 * @private
 */
opendxp.bundle.staticroutes.startup = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
    },

    preMenuBuild: function (e) {
        let menu = e.detail.menu;
        const user = opendxp.globalmanager.get('user');
        const perspectiveCfg = opendxp.globalmanager.get("perspective");

        if (user.isAllowed("routes") && perspectiveCfg.inToolbar("settings.routes")) {
            menu.settings.items.push({
                text: t("static_routes"),
                iconCls: "opendxp_nav_icon_routes",
                priority: 95,
                itemId: 'opendxp_menu_settings_static_routes',
                handler: this.editRoutes
            });
        }
    },

    editRoutes: function () {

        try {
            opendxp.globalmanager.get("bundle_staticroutes").activate();
        }
        catch (e) {
            opendxp.globalmanager.add("bundle_staticroutes", new opendxp.bundle.staticroutes.settings());
        }
    }
})

const opendxpBundleStaticroutes = new opendxp.bundle.staticroutes.startup();