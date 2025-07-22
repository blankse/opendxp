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

opendxp.registerNS("opendxp.bundle.wordexport.settings");
/**
 * @private
 */
opendxp.bundle.wordexport.settings = Class.create({

    initialize: function () {
        this.getTabPanel();
    },


    activate: function () {
        var tabPanel = Ext.getCmp("opendxp_panel_tabs");
        tabPanel.setActiveItem("opendxp_word");
    },

    getTabPanel: function () {

        if (!this.panel) {
            this.panel = new Ext.Panel({
                id: "opendxp_word",
                title: "Microsoft® Word " + t("export"),
                iconCls: "opendxp_icon_docx",
                border: false,
                layout: "fit",
                closable:true,
                items: [this.getExportPanel()]
            });

            var tabPanel = Ext.getCmp("opendxp_panel_tabs");
            tabPanel.add(this.panel);
            tabPanel.setActiveItem("opendxp_word");

            this.panel.on("destroy", function () {
                opendxp.globalmanager.remove("bundle_word_export");
            }.bind(this));

            opendxp.layout.refresh();
        }

        return this.panel;
    },

    getExportPanel: function () {

        this.exportStore = new Ext.data.ArrayStore({
            fields: [
                "id",
                "path",
                "type",
                "children"
            ]
        });

        const componentToolbarItems = [
            {
                xtype: "tbspacer",
                width: 24,
                height: 24,
                cls: "opendxp_icon_droptarget"
            },
            t("elements_to_export"),
            "->",
            {
                xtype: "button",
                iconCls: "opendxp_icon_delete",
                handler: function () {
                    this.exportStore.removeAll();
                }.bind(this)
            }
        ];

        if(opendxp.helpers.hasSearchImplementation()) {
            componentToolbarItems.push({
                xtype: "button",
                iconCls: "opendxp_icon_search",
                handler: function () {
                    opendxp.helpers.itemselector(true, function (items) {
                        if (items.length > 0) {
                            for (var i = 0; i < items.length; i++) {
                                this.exportStore.add({
                                    id: items[i].id,
                                    path: items[i].fullpath,
                                    type: items[i].type,
                                    children: true
                                });
                            }
                        }
                    }.bind(this), {
                        type: ["object", "document"]
                    });
                }.bind(this)
            });
        }

        this.component = Ext.create('Ext.grid.Panel', {
            store: this.exportStore,
            autoHeight: true,
            border: true,
            style: "margin-bottom: 10px",
            selModel: Ext.create('Ext.selection.RowModel', {}),
            columns: {
                defaults: {
                    sortable: false
                },
                items: [
                    {text: 'ID', dataIndex: 'id', width: 50},
                    {text: t("path"), dataIndex: 'path', flex: 200},
                    {text: t("type"), dataIndex: 'type', width: 100},
                    Ext.create('Ext.grid.column.Check', {
                        text: t("children"),
                        dataIndex: "children",
                        width: 100
                    }),
                    {
                        xtype: 'actioncolumn',
                        menuText: t('remove'),
                        width: 30,
                        items: [{
                            tooltip: t('remove'),
                            icon: "/bundles/opendxpadmin/img/flat-color-icons/delete.svg",
                            handler: function (grid, rowIndex) {
                                grid.getStore().removeAt(rowIndex);
                            }.bind(this)
                        }]
                    }
                ]
            },
            tbar: componentToolbarItems
        });

        this.component.on("afterrender", function () {

            var dropTargetEl = this.component.getEl();
            var gridDropTarget = new Ext.dd.DropZone(dropTargetEl, {
                ddGroup    : 'element',
                getTargetFromEvent: function(e) {
                    return this.component.getEl().dom;
                }.bind(this),

                onNodeOver: function (overHtmlNode, ddSource, e, data) {
                    if (data.records.length == 1) {
                        data = data.records[0].data;
                        var type = data.elementType;

                        if (type == "document" || type == "object") {
                            return Ext.dd.DropZone.prototype.dropAllowed;
                        }
                    }

                    return Ext.dd.DropZone.prototype.dropNotAllowed;

                }.bind(this),

                onNodeDrop : function(target, dd, e, data) {
                    if (opendxp.helpers.dragAndDropValidateSingleItem(data)) {
                        data = data.records[0].data;

                        var type = data.elementType;
                        if (type == "document" || type == "object") {
                            this.exportStore.add({
                                id: data.id,
                                path: data.path,
                                type: data.elementType,
                                children: true
                            });
                            return true;
                        }
                    }
                    return false;
                }.bind(this)
            });
        }.bind(this));

        var languagestore = [];
        for (var i=0; i<opendxp.settings.websiteLanguages.length; i++) {
            languagestore.push([opendxp.settings.websiteLanguages[i],opendxp.settings.websiteLanguages[i]]);
        }

        this.exportSourceLanguageSelector = new Ext.form.ComboBox({
            fieldLabel: t("source"),
            name: "source",
            store: languagestore,
            editable: false,
            triggerAction: 'all',
            mode: "local",
            listWidth: 200
        });

        this.exportPanel = new Ext.Panel({
            title: t("export"),
            autoScroll: true,
            region: "center",
            bodyStyle: "padding: 10px",
            items: [{
                title: t("important_notice"),
                html: '<div style="font-weight: bold">' + t("microsoft_word_export_notice") + '</div>',
                style: "margin-bottom: 10px",
                iconCls: "opendxp_icon_warning"
            }, this.component, {
                xtype: "form",
                title: t("language"),
                bodyStyle: "padding: 10px",
                items: [this.exportSourceLanguageSelector],
                style: "margin-bottom: 10px"
            }],
            buttons: [{
                text: t("export"),
                iconCls: "opendxp_icon_export",
                handler: this.startExport.bind(this)
            }]
        });

        return this.exportPanel;
    },

    startExport: function () {
        var tmData = [];

        var data = this.exportStore.queryBy(function(record, id) {
            return true;
        });

        // skip if no items are selected to export
        if(data.items.length < 1) {
            return;
        }

        for (var i = 0; i < data.items.length; i++) {
            tmData.push(data.items[i].data);
        }

        Ext.Ajax.request({
            url: Routing.generate('opendxp_admin_translation_contentexportjobs'),
            method: 'POST',
            params: {
                source: this.exportSourceLanguageSelector.getValue(),
                data: Ext.encode(tmData),
                type: "word",
                job_url: Routing.generate('opendxp_bundle_wordexport_translation_wordexport'),
                elements_per_job: 1
            },
            success: function(response) {
                var res = Ext.decode(response.responseText);

                this.exportProgressbar = new Ext.ProgressBar({
                    text: t('initializing')
                });

                this.exportProgressWin = new Ext.Window({
                    title: t("export"),
                    layout:'fit',
                    width:200,
                    bodyStyle: "padding: 10px;",
                    closable:false,
                    plain: true,
                    items: [this.exportProgressbar],
                    listeners: opendxp.helpers.getProgressWindowListeners()
                });

                this.exportProgressWin.show();


                var pj = new opendxp.tool.paralleljobs({
                    success: function (id) {
                        if(this.exportProgressWin) {
                            this.exportProgressWin.close();
                        }

                        this.exportProgressbar = null;
                        this.exportProgressWin = null;

                        opendxp.helpers.download(Routing.generate('opendxp_bundle_wordexport_translation_wordexportdownload', {id: id}));
                    }.bind(this, res.id),
                    update: function (currentStep, steps, percent) {
                        if(this.exportProgressbar) {
                            var status = currentStep / steps;
                            this.exportProgressbar.updateProgress(status, percent + "%");
                        }
                    }.bind(this),
                    failure: function (message) {
                        console.error("Word export: " + message);
                    }.bind(this),
                    stopOnError: false,
                    jobs: res.jobs
                });
            }.bind(this)
        });
    }
});
