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
opendxp.registerNS("opendxp.bundle.xliff.startup");


opendxp.bundle.xliff.startup = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
        document.addEventListener(opendxp.events.onPerspectiveEditorLoadPermissions, this.onPerspectiveEditorLoadPermissions.bind(this));
    },

    preMenuBuild: function (e) {

        let menu = e.detail.menu;
        let that = this;
        const user = opendxp.globalmanager.get('user');
        const perspectiveCfg = opendxp.globalmanager.get("perspective");

        if (user.isAllowed("xliff_import_export") && perspectiveCfg.inToolbar("extras.xliff_import_export")) {

            menu.extras.items.some(function(item, index) {
                if (item.itemId === 'opendxp_menu_extras_translations'){
                    menu.extras.items[index].menu.items.push({
                        text: "XLIFF " + t("export") + "/" + t("import"),
                        iconCls: "opendxp_nav_icon_translations",
                        itemId: 'opendxp_menu_extras_translations_xliff',
                        handler: that.xliffImportExport,
                        priority: 20,
                    });
                    return true;
                }
            });
        }
    },

    xliffImportExport: function() {
        try {
            opendxp.globalmanager.get("bundle_xliff").activate();
        } catch (e) {
            opendxp.globalmanager.add("bundle_xliff", new opendxp.bundle.xliff.settings());
        }
    },

    onPerspectiveEditorLoadPermissions: function (e) {
        let context = e.detail.context;
        let menu = e.detail.menu;
        let permissions = e.detail.permissions;

        if(context === 'toolbar' && menu === 'extras' &&
            permissions[context][menu].indexOf('items.xliff_import_export') === -1) {
            permissions[context][menu].push('items.xliff_import_export');
        }
    }
})

const bundle_xliff = new opendxp.bundle.xliff.startup();