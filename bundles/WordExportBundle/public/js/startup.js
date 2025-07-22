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

opendxp.registerNS("opendxp.bundle.wordexport.startup");
/**
 * @private
 */
opendxp.bundle.wordexport.startup = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
    },

    preMenuBuild: function (e) {

        let menu = e.detail.menu;
        let that = this;
        const user = opendxp.globalmanager.get('user');
        const perspectiveCfg = opendxp.globalmanager.get("perspective");
        if (user.isAllowed("word_export") && perspectiveCfg.inToolbar("extras.word_export")) {
            menu.extras.items.some(function(item, index) {
                if (item.itemId === 'opendxp_menu_extras_translations'){
                    menu.extras.items[index].menu.items.push({
                        text: "Microsoft® Word " + t("export"),
                        iconCls: "opendxp_nav_icon_word_export",
                        priority: 25,
                        itemId: 'opendxp_menu_extras_translations_word_export',
                        handler: that.wordExport
                    });
                    return true;
                }
            });
        }
    },

    wordExport: function () {
        try {
            opendxp.globalmanager.get("bundle_word_export").activate();
        }
        catch (e) {
            opendxp.globalmanager.add("bundle_word_export", new opendxp.bundle.wordexport.settings());
        }
    }
})

const opendxpBundleWordExport = new opendxp.bundle.wordexport.startup();