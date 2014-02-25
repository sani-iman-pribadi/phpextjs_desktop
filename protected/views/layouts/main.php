<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>PHPExtJs</title>
        <!-- <x-compile> -->
        <!-- <x-bootstrap> -->

        <link rel="stylesheet" type="text/css" href="css/desktop.css" />

        <script type="text/javascript" src="shared/include-ext.js"></script>
        <script type="text/javascript" src="shared/options-toolbar.js"></script>
        <!--
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        -->
        
        <script src="app/app.js"></script>


        <!-- </x-bootstrap> -->
        <script type="text/javascript">

                    Ext.Loader.setPath({
                    'Ext.ux.desktop': 'js',
                            PHPExtJS: 'app'
                    });
                    var name = '';
                    var id = '';
                    Ext.define('User', {
                        config: {
                            id: '',
                            name: '',
                        },
                        constructor: function(config) {
                        this.initConfig(config);
                                return this;
                        }
                    });
                    var user = new User();
                    <?php
                        if(isset(Yii::app()->user->get()->id)){
                            echo 'user.id =' .  Yii::app()->user->get()->id . ';' . "\n";
                            echo 'user.name ="' .  Yii::app()->user->get()->name . '";' . "\n";
                        }
                    ?>
<?php

    function subMenuItems($models) {

        echo 'menu:{';
        echo 'items:[';

        foreach ($models as $data) {
            $permissionTrue = UserMenu::model()->find('menu_id=:menu_id', array(':menu_id' => $data['id']));
            if (isset($permissionTrue)) {
                if ($data['status'] == '1') {
                    echo '{';
                    echo 'text:"' . $data['text'] . '",';
                    echo 'iconCls:"' . $data['iconCls'] . '",';
                    if ($data['type'] == 'root') {
                        echo 'handler:function{return false}';
                    } else {
                        echo 'handler:this.' . $data['handler'] . ',';
                    }
                    echo 'scope:this,';
                    if (isset($data['items']) && !empty($data['items'])) {
                        subMenuItems($data['items']);
                    }
                    echo '},';
                }
            }
        }

        echo '],';
        echo '},';
    }

    function menuItem($models) {

        foreach ($models as $data) {

            if (isset($data['items']) && !empty($data['items'])) {
                subMenuItems($data['items']);
            }
        }
    }
?>

<?php $roots = UserMenu::model()->with('menu')->findAll('menu.type=:type AND menu.status=1', array(':type' => 'root')); ?>


            Ext.define('PHPExtJS.App', {
            extend: 'Ext.ux.desktop.App',
                    requires: [
                            'Ext.window.MessageBox',
                            'Ext.ux.desktop.ShortcutModel',
                            'PHPExtJS.view.Settings',
                            'PHPExtJS.view.SystemStatus',
                            'PHPExtJS.view.Notepad',
                            'PHPExtJS.view.BogusMenuModule',
                            'PHPExtJS.view.BogusModule',
                            
                            <?php foreach ($roots as $root) { ?>
                            'PHPExtJS.view.<?php echo $root['menu']['module'] . '.Module'; ?>',
                            <?php } ?>
                    ],
                    init: function() {
                    this.callParent();
                    },
                    getModules: function() {
                    return [
                            new PHPExtJS.view.SystemStatus(),
                            new PHPExtJS.view.Notepad(),
                            new PHPExtJS.view.BogusMenuModule(),
                            new PHPExtJS.view.BogusModule(),     
                            
                            <?php
                                foreach ($roots as $root) {
                            ?>

                            new PHPExtJS.view.<?php echo $root['menu']['module'] . '.Module'; ?>(), // ex: new Agama(),

                            <?php
                                }
                            ?>
                    ];
                    },
                    //menampilkan shortcut di desktop

                    getDesktopConfig: function() {
                    var me = this, ret = me.callParent();
                            return Ext.apply(ret, {
                            //cls: 'ux-desktop-black',
                            contextMenuItems: [
                            {text: 'Change Settings', handler: me.onSettings, scope: me}
                            ],
                                    shortcuts: Ext.create('Ext.data.Store', {
                                    model: 'Ext.ux.desktop.ShortcutModel',
                                            data: [
                                                {name: 'Grid Window', iconCls: 'grid-shortcut', module: 'grid-win' },
                                                {name: 'User Account', iconCls: 'accordion-shortcut', module: 'acc-win'},
                                                {name: 'Notepad', iconCls: 'notepad-shortcut', module: 'notepad'},
                                                {name: 'System Status', iconCls: 'cpu-shortcut', module: 'systemstatus'}
                                            ]
                                    }),
                                    wallpaper: 'wallpapers/phpextjs.jpg',
                                    wallpaperStretch: true
                            });
                    },
                    // config for the start menu
                    getStartConfig: function() {
                        
                    var me = this, ret = me.callParent();
                            
                            return Ext.apply(ret, {
                            title: '<i>Welcome</i>',
                            iconCls: 'user',
                            height: 300,
                            toolConfig: {
                            width: 100,
                                    items: [
                                        {
                                            text: 'Settings',
                                            iconCls: 'settings',
                                            handler: me.onSettings,
                                            scope: me
                                        },
                                                '-',
                                        {
                                            text: 'Logout',
                                            id:'logoutButton',
                                            iconCls: 'logout',
                                            hidden:user.id==''?true:false,
                                            handler: me.onLogout,
                                            scope: me
                                        },
                                        {
                                            text: 'Login',
                                            id:'loginButton',
                                            iconCls: 'login',
                                            hidden:user.id==''?false:true,
                                            handler: me.onLogin,
                                            scope: me
                                        }                                        
                                    ]
                            }
                            });
                            
                    },
                    getTaskbarConfig: function() {
                    var ret = this.callParent();
                            return Ext.apply(ret, {
                            quickStart: [
                                    { name: 'Accordion Window', iconCls: 'accordion', module: 'acc-win' },
                                    { name: 'Grid Window', iconCls: 'icon-grid', module: 'grid-win' }
                            ],
                                    trayItems: [
                                        {xtype: 'trayclock', flex: 1}
                                    ]
                            });
                    },
                    onLogout: function() {
                        Ext.Msg.confirm('Logout', 'Are you sure you want to logout?', function(btn, text) {
                            if (btn == 'yes') {
                                Ext.Ajax.request({
                                    url: window.location.pathname + 'site/logout',
                                    success: function(response, opts) {
                                        var data = Ext.JSON.decode(response.responseText);
                                        if (data.success === true) {
                                            Ext.getCmp('loginButton').setVisible(true);
                                            Ext.getCmp('logoutButton').setVisible(false);
                                        }
                                    },
                                    failure: function(response, opts) {
                                        Ext.MessageBox.alert('Status', 'Server-side failure with status code ' + response.status);
                                    },
                                });
                            }
                        })
                    },
                    onSettings: function() {
                            var dlg = new PHPExtJS.view.Settings({
                                desktop: this.desktop
                            });
                            dlg.show();
                    },
                    onLogin: function() {
                           var dlg = new PHPExtJS.view.Login._form({
                               desktop: this.desktop
                           });
                           dlg.show();
                   }                   
            });
            
            var myDesktopApp;
            Ext.onReady(function() {
                myDesktopApp = new PHPExtJS.App();
            });
        </script>
        <!-- </x-compile> -->

        <style>
            h1 {
                font-size:18px;
                margin-bottom:20px;
            }
            h2 {
                font-size:14px;
                color:#333;
                font-weight:bold;
                margin:10px 0;
            }
        </style>
    </head>

    <body>

    </body>
</html>