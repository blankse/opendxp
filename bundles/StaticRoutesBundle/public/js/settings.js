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

opendxp.registerNS("opendxp.bundle.staticroutes.settings");
/**
 * @private
 */
opendxp.bundle.staticroutes.settings = Class.create({

    initialize:function () {

        this.getTabPanel();
    },

    activate:function () {
        var tabPanel = Ext.getCmp("opendxp_panel_tabs");
        tabPanel.setActiveItem("opendxp_staticroutes");
    },

    getTabPanel:function () {

        if (!this.panel) {
            this.panel = new Ext.Panel({
                id:"opendxp_staticroutes",
                title:t("static_routes"),
                iconCls:"opendxp_icon_routes",
                border:false,
                layout:"fit",
                closable:true,
                items:[this.getRowEditor()]
            });

            var tabPanel = Ext.getCmp("opendxp_panel_tabs");
            tabPanel.add(this.panel);
            tabPanel.setActiveItem("opendxp_staticroutes");


            this.panel.on("destroy", function () {
                opendxp.globalmanager.remove("bundle_staticroutes");
            }.bind(this));

            opendxp.layout.refresh();
        }

        return this.panel;
    },

    getRowEditor:function () {

        var url = Routing.generate('opendxp_bundle_staticroutes_settings_staticroutes');

        this.store = opendxp.helpers.grid.buildDefaultStore(
            url,
            [
                {name:'id'},
                {name:'name'},
                {name:'pattern', allowBlank:false},
                {name:'reverse', allowBlank:true},
                {name:'controller'},
                {name:'variables'},
                {name:'defaults'},
                {name:'siteId'},
                {name:'priority', type:'int'},
                {name:'methods'},
                {name:'creationDate'},
                {name:'modificationDate'}
            ], null, {
                remoteSort: false,
                remoteFilter: false
            }
        );
        this.store.setAutoSync(true);

        this.filterField = new Ext.form.TextField({
            width:200,
            style:"margin: 0 10px 0 0;",
            enableKeyEvents:true,
            listeners:{
                "keydown":function (field, key) {
                    if (key.getKey() == key.ENTER) {
                        var input = field;
                        var proxy = this.store.getProxy();
                        proxy.extraParams.filter = input.getValue();
                        this.store.load();
                    }
                }.bind(this)
            }
        });

        var typesColumns = [
            {text:t("name"), flex:50, sortable:true, dataIndex:'name',
                editor:new Ext.form.TextField()},
            {text:t("pattern"), flex:100, sortable:true, dataIndex:'pattern',
                editor:new Ext.form.TextField()},
            {text:t("reverse"), flex:100, sortable:true, dataIndex:'reverse',
                editor:new Ext.form.TextField()},
            {text:t("controller"), flex:200, sortable:false, dataIndex:'controller',
                editor:new Ext.form.ComboBox({
                    store:new Ext.data.JsonStore({
                        autoDestroy:true,
                        autoLoad: true,
                        proxy: {
                            type: 'ajax',
                            url: Routing.generate('opendxp_admin_misc_getavailablecontroller_references'),
                            reader: {
                                type: 'json',
                                rootProperty: 'data'
                            }
                        },
                        fields:["name"]
                    }),
                    matchFieldWidth: false,
                    typeAhead: true,
                    queryMode: "local",
                    anyMatch: true,
                    editable: true,
                    forceSelection: false,
                    triggerAction:"all",
                    displayField:'name',
                    valueField:'name',
                    listConfig: {
                        maxWidth: 400
                    }
                })},
            {text:t("variables"), flex:50, sortable:false, dataIndex:'variables',
                editor:new Ext.form.TextField()},
            {text:t("defaults"), flex:50, sortable:false, dataIndex:'defaults',
                editor:new Ext.form.TextField()},
            {text:t("site_ids"), flex:100, sortable:true, dataIndex:"siteId",
                editor:new Ext.form.TextField(),
                tooltip: t("site_ids_tooltip")
            },
            {text:t("priority"), flex:50, sortable:true, dataIndex:'priority', editor:new Ext.form.ComboBox({
                store:[1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                mode:"local",
                triggerAction:"all"
            })},
            {text:t("methods"), flex:50, sortable:false, dataIndex:'methods',
                editor:new Ext.form.TextField(),
            },
            {text: t("creationDate"), sortable: true, dataIndex: 'creationDate', editable: false,
                hidden: true,
                renderer: function(d) {
                    if (d !== undefined) {
                        var date = new Date(d * 1000);
                        return Ext.Date.format(date, "Y-m-d H:i:s");
                    } else {
                        return "";
                    }
                }
            },
            {text: t("modificationDate"), sortable: true, dataIndex: 'modificationDate', editable: false,
                hidden: true,
                renderer: function(d) {
                    if (d !== undefined) {
                        var date = new Date(d * 1000);
                        return Ext.Date.format(date, "Y-m-d H:i:s");
                    } else {
                        return "";
                    }
                }
            },
            {
                xtype:'actioncolumn',
                menuText: t('delete'),
                width: 40,
                items: [{
                    getClass: function (v, meta, rec) {
                        var klass = "opendxp_action_column ";
                        if (rec.data.writeable) {
                            klass += "opendxp_icon_minus";
                        }
                        return klass;
                    },
                    tooltip: t('delete'),
                    handler: function (grid, rowIndex) {
                        var data = grid.getStore().getAt(rowIndex);
                        if (!data.data.writeable) {
                            return;
                        }

                        const decodedName = Ext.util.Format.htmlDecode(data.data.name);

                        opendxp.helpers.deleteConfirm(
                            t('staticroute'),
                            Ext.util.Format.htmlEncode(decodedName),
                            function () {
                                grid.getStore().removeAt(rowIndex);
                            }.bind(this)
                        );
                    }.bind(this)
                }]
            }
        ];

        this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
            clicksToEdit: 1,
            clicksToMoveEditor: 1,
            listeners: {
                beforeedit: function (editor, context, eOpts) {
                    if (!context.record.data.writeable) {
                        return false;
                    }
                }
            }
        });


        this.grid = Ext.create('Ext.grid.Panel', {
            frame:false,
            autoScroll:true,
            store:this.store,
            columnLines:true,
            bodyCls: "opendxp_editable_grid",
            trackMouseOver:true,
            stripeRows:true,
            columns: {
                items: typesColumns,
                defaults: {
                    renderer: Ext.util.Format.htmlEncode
                },
            },
            sm: Ext.create('Ext.selection.RowModel', {}),
            plugins: [
                this.rowEditing
            ],
            tbar: {
                cls: 'opendxp_main_toolbar',
                items: [
                    {
                        text:t('add'),
                        handler:this.onAdd.bind(this),
                        iconCls:"opendxp_icon_add",
                        disabled: !opendxp.settings['staticroutes-writeable']
                    },
                    "->",
                    {
                        text:t("filter") + "/" + t("search"),
                        xtype:"tbtext",
                        style:"margin: 0 10px 0 0;"
                    },
                    this.filterField
                ]
            },
            viewConfig:{
                forceFit:true,
                getRowClass: function (record, rowIndex) {
                    return record.data.writeable ? '' : 'opendxp_grid_row_disabled';
                }
            }
        });

        return this.grid;
    },


    onAdd:function (btn, ev) {
        var u = {
            name: ""
        };

        this.grid.store.add(u);
    }
});
