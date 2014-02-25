Ext.define('PHPExtJS.view.Login.Module', {
    extend: 'Ext.ux.desktop.Module',
	
    requires: [
        'PHPExtJS.view.Login._form',
    ],
    
    init : function(){
        this.launcher = {
            text: 'Login',
            iconCls:'bogus'
        }
    },
    
    createWindow : function(src){
        var desktop = this.app.getDesktop();
        var win = desktop.getWindow('login');
        if(!win){
            win = desktop.createWindow({
                id: 'login',
                title:'Login',
                iconCls: 'bogus',
                animCollapse:false,
                border: false,               
                hideMode: 'offsets',
                layout: {
                        type: 'fit',
                        align: 'stretch'  // Child items are stretched to full width
                },
                items:[{
                        xtype: 'loginForm'
                }]
                
            });
        }
        win.show();
        return win;
    },
            
});

