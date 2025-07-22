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

opendxp.registerNS("opendxp.bundle.customreports.custom.settings");
/**
 * @private
 */
opendxp.bundle.customreports.custom.settings = Class.create({

    initialize: function (parent) {
        this.getPanel();
    },

    activate: function () {
        var tabPanel = Ext.getCmp("opendxp_panel_tabs");
        tabPanel.setActiveItem("opendxp_custom_reports_settings");
    },

    getPanel: function () {

        var editor = new opendxp.bundle.customreports.custom.panel();

        if (!this.panel) {
            this.panel = new Ext.Panel({
                id: "opendxp_custom_reports_settings",
                title: t("custom_reports"),
                iconCls: "opendxp_icon_reports",
                layout: "fit",
                closable:true,
                items: [editor.getTabPanel()]
            });

            var tabPanel = Ext.getCmp("opendxp_panel_tabs");
            tabPanel.add(this.panel);
            tabPanel.setActiveItem("opendxp_custom_reports_settings");

            this.panel.on("destroy", function () {
                opendxp.globalmanager.remove("custom_reports_settings");
            }.bind(this));

            opendxp.layout.refresh();
        }

        return this.panel;
    }
});
