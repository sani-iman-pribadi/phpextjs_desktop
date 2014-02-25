Ext.define('PHPExtJS.view.Login._form', {
    extend: 'Ext.window.Window',
    alias: 'widget.loginForm',
    requires: ['Ext.form.Panel', 'Ext.form.field.Text'],
    title: 'Login Form',
    autoShow: true,
    width: 350,    
    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'form',
                    id:'loginForm',
                    padding: 10,
                    border: true,
                    style: 'background-color: #fff;',
                    bodyPadding: 10,
                    defaultType: 'textfield',                 
                    defaults: {
                        anchor: '100%'
                    },
                    items: [
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            name: 'username',
                            fieldLabel: 'Username',
                            msgTarget: 'side',
                            allowBlank: false,
                            maxLength: 255
                        },
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            name: 'password',
                            fieldLabel: 'Password',
                            inputType: 'password',
                            msgTarget: 'side',
                            allowBlank: false,
                            maxLength: 255
                        },
                    ],
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'bottom',
                            items: [
                                {
                                    xtype: 'tbfill'
                                },
                                {
                                    xtype: 'button',
                                    action: 'submit',
                                    formBind: true,
                                    iconCls: 'icon-save',
                                    text: 'Submit'
                                }
                            ]
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }
});
