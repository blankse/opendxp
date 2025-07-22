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

opendxp.registerNS("opendxp.bundle.customreports.broker");
/**
 * @private
 */
opendxp.bundle.customreports.broker = {

    reports: {},
    groups: [
        {
            id: "other",
            name: t("other")
        }
    ],

    groupIds: [],

    addGroup: function (id, name, iconCls) {

        if (!in_array(id, this.groupIds)) {
            this.groups.push({
                id: id,
                name: t(name),
                iconCls: iconCls
            });
        }

        this.groupIds.push(id);
    },

    addReport: function (reportClass, groupId, config) {
        if (!groupId) {
            groupId = "other";
        }

        if (typeof this.reports[groupId] != "object") {
            this.reports[groupId] = [];
        }

        this.reports[groupId].push({
            "class": reportClass,
            config: config
        });
    }
};