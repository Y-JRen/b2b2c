<aside class="main-sidebar">

    <section class="sidebar">

        <?= \common\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    [
                        'label' => '商品管理',
                        'icon' => 'dashboard',
                        'options' => ['class' => 'active treeview',],
                        'items' => [
                            ['label' => '商品列表', 'icon' => 'circle-o', 'url' => ['/spu/index']],
                        ]
                    ],
                    [
                        'label' => '订单管理',
                        'icon' => 'dashboard',
                        'options' => ['class' => 'active treeview',],
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'circle-o', 'url' => ['/gii']],
                        ]
                    ],
					
					[
                        'label' => '金融管理',
                        'icon' => 'dashboard',
                        'options' => ['class' => 'active treeview',],
                        'items' => [
                            ['label' => '金融方案', 'icon' => 'circle-o', 'url' => ['/financial/index']],
                        ]
                    ],
					
					
                    [
                        'label' => '商户管理',
                        'icon' => 'dashboard',
                        'options' => ['class' => 'active treeview',],
                        'items' => [
                            ['label' => '商户列表', 'icon' => 'circle-o', 'url' => ['/partner/index']],
                        ]
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
