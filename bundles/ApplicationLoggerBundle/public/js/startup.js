opendxp.registerNS('opendxp.bundle.applicationlogger.startup');

opendxp.bundle.applicationlogger.startup = Class.create({

    initialize: function () {
        document.addEventListener(opendxp.events.preRegisterKeyBindings, this.registerKeyBinding.bind(this));
        document.addEventListener(opendxp.events.preMenuBuild, this.preMenuBuild.bind(this));
        document.addEventListener(opendxp.events.opendxpReady, this.opendxpReady.bind(this));
    },

    opendxpReady: function () {
        this.registerApplicationLoggerPanel();
    },

    registerApplicationLoggerPanel: function () {
        this.applicationLoggerPanel = opendxp.globalmanager.get('applicationLoggerPanelImplementationFactory');

        this.applicationLoggerPanel.registerImplementation(opendxp.bundle.applicationlogger.log.admin);
    },

    preMenuBuild: function (e) {
        const user = opendxp.globalmanager.get('user');
        const perspectiveCfg = opendxp.globalmanager.get('perspective');
        let menu = e.detail.menu;

        if (user.isAllowed('application_logging') && perspectiveCfg.inToolbar('extras.applicationlog')) {
            menu.extras.items.push({
                text: t('log_applicationlog'),
                iconCls: 'opendxp_nav_icon_log_admin',
                itemId: 'opendxp_menu_extras_application_log',
                handler: this.logAdmin
            });
        }
    },

    logAdmin: function () {
        try {
            opendxp.globalmanager.get('opendxp_applicationlog_admin').activate();
        } catch (e) {
            opendxp.globalmanager.add('opendxp_applicationlog_admin', new opendxp.bundle.applicationlogger.log.admin());
        }
    },

    registerKeyBinding: function (e) {
        const user = opendxp.globalmanager.get('user');
        if (user.isAllowed('application_logging')) {
            opendxp.helpers.keyBindingMapping.applicationLogger = function () {
                applicationLogger.logAdmin();
            }
        }
    }
})

const applicationLogger = new opendxp.bundle.applicationlogger.startup();