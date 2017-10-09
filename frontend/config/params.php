<?php
return [
    'adminEmail' => 'admin@example.com',
    'arrApiErrorInfo' => require(__DIR__ . '/error.php'),

    // 分页大小
    'pageSize' => 20,

    // 合作商出售价格区间
    'arrPriceInterval' => [
        ['min_price' => 0, 'max_price' => 8, 'name' => '8万以下'],
        ['min_price' => 8, 'max_price' => 12, 'name' => '8 - 12万'],
        ['min_price' => 12, 'max_price' => 15, 'name' => '12 - 15万'],
        ['min_price' => 15, 'max_price' => 20, 'name' => '15 - 20万'],
        ['min_price' => 20, 'max_price' => 25, 'name' => '20 - 25万'],
        ['min_price' => 25, 'max_price' => 50, 'name' => '25 - 50万'],
        ['min_price' => 50, 'max_price' => 0, 'name' => '50万以上'],
    ],

    // 首付金额
    'arrDownPayment' => [
        ['min_price' => 0, 'max_price' => 1, 'name' => '一万元以下'],
        ['min_price' => 1, 'max_price' => 2, 'name' => '1-2万'],
        ['min_price' => 2, 'max_price' => 3, 'name' => '2-3万'],
        ['min_price' => 3, 'max_price' => 5, 'name' => '3-5万'],
        ['min_price' => 5, 'max_price' => 8, 'name' => '5-8万'],
        ['min_price' => 8, 'max_price' => 10, 'name' => '8-10万'],
        ['min_price' => 10, 'max_price' => 15, 'name' => '10-15万'],
        ['min_price' => 15, 'max_price' => 0, 'name' => '15万以上'],
    ],


];
