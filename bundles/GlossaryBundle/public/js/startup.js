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

opendxp.registerNS("opendxp.bundle.glossary.startup");
/**
 * @private
 */
opendxp.bundle.glossary.startup = Class.create({

    initialize: function () {
        document.addEventListener(opendxp.events.preRegisterKeyBindings, this.registerKeyBinding.bind(this));
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
    },

    preMenuBuild: function (e) {
        let menu = e.detail.menu;
        const user = opendxp.globalmanager.get('user');
        const perspectiveCfg = opendxp.globalmanager.get("perspective");

        if (menu.extras && user.isAllowed("glossary") && perspectiveCfg.inToolbar("extras.glossary")) {
            menu.extras.items.push({
                text: t("glossary"),
                iconCls: "opendxp_nav_icon_glossary",
                priority: 5,
                itemId: 'opendxp_menu_extras_glossary',
                handler: this.editGlossary,
            });
        }
    },

    editGlossary: function() {
        try {
            opendxp.globalmanager.get("bundle_glossary").activate();
        } catch (e) {
            opendxp.globalmanager.add("bundle_glossary", new opendxp.bundle.glossary.settings());
        }
    },

    registerKeyBinding: function(e) {
        const user = opendxp.globalmanager.get('user');
        if (user.isAllowed("glossary")) {
            opendxp.helpers.keyBindingMapping.glossary = function() {
                opendxpBundleGlossary.editGlossary();
            }
        }
    }
})

const opendxpBundleGlossary = new opendxp.bundle.glossary.startup();


