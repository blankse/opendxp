opendxp.registerNS("opendxp.bundle.customreports.startup");


opendxp.bundle.customreports.startup = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.preRegisterKeyBindings, this.registerKeyBinding.bind(this));
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
        document.addEventListener(opendxp.events.opendxpReady, this.opendxpReady.bind(this));
    },

    opendxpReady: function () {
        this.registerCustomReportsPanel();
    },

    registerCustomReportsPanel: function () {
        this.customReportsPanel = opendxp.globalmanager.get('customReportsPanelImplementationFactory');

        this.customReportsPanel.registerImplementation(opendxp.bundle.customreports.panel);
    },

    preMenuBuild: function (e) {
        let menu = e.detail.menu;
        const user = opendxp.globalmanager.get('user');
        const perspectiveCfg = opendxp.globalmanager.get("perspective");

        if(menu.marketing) {
            if (user.isAllowed("reports") && perspectiveCfg.inToolbar("marketing.reports")) {
                menu.marketing.items.push({
                    text: t("reports"),
                    priority: 5,
                    iconCls: "opendxp_nav_icon_reports",
                    itemId: 'opendxp_menu_marketing_reports',
                    handler: this.showReports.bind(this, null)
                });
            }

            if (user.isAllowed("reports_config") && perspectiveCfg.inToolbar("settings.customReports")) {
                menu.marketing.items.push({
                    text: t("custom_reports"),
                    priority: 6,
                    iconCls: "opendxp_nav_icon_reports",
                    itemId: 'opendxp_menu_marketing_custom_reports',
                    handler: this.showCustomReports
                });
            }
        }
    },

    showCustomReports: function () {
        try {
            opendxp.globalmanager.get("custom_reports_settings").activate();
        }
        catch (e) {
            opendxp.globalmanager.add("custom_reports_settings", new opendxp.bundle.customreports.custom.settings());
        }
    },

    showReports: function (reportClass, reportConfig) {
        try {
            opendxp.globalmanager.get("reports").activate();
        }
        catch (e) {
            opendxp.globalmanager.add("reports", this.customReportsPanel.getNewReportInstance());
        }

        // this is for generated/configured reports like the SQL Report
        try {
            if(reportClass) {
                opendxp.globalmanager.get("reports").openReportViaToolbar(reportClass, reportConfig);
            }
        } catch (e) {
            console.log(e);
        }
    },

    registerKeyBinding: function(e) {
        const user = opendxp.globalmanager.get('user');
        if (user.isAllowed("reports_config")) {
            opendxp.helpers.keyBindingMapping.customReports = function() {
                customreports.showCustomReports();
            }
        }
        if (user.isAllowed("reports")) {
            opendxp.helpers.keyBindingMapping.reports = function() {
                customreports.showReports();
            }
        }
    }
})

const customreports = new opendxp.bundle.customreports.startup();