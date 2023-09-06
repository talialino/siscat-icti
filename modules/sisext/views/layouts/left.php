<aside class="main-sidebar">

    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<? echo "/siscat/images/".Yii::$app->controller->module->id.".png"?>" style="width: 32px;height: 32px;">
            </div>
            <div class="pull-left info">
                <p><?=isset(Yii::$app->controller->module->left) ? Yii::$app->controller->module->left : Yii::$app->controller->module->id?></p>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget( isset(Yii::$app->controller->module->menu) ? Yii::$app->controller->module->menu : 
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    
                    ['label' => Yii::t('user','Users'), 'icon' => 'users', 'url' => ['/user/admin'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin],
                    ['label' => 'Permissões/Setor', 'icon' => 'unlock-alt', 'url' => ['/authitemsisrhsetor'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'], 'visible' => YII_ENV_DEV && Yii::$app->user->can('siscatAdmin')],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'], 'visible' => YII_DEBUG && Yii::$app->user->can('siscatAdmin')],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#','visible' => false,
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
