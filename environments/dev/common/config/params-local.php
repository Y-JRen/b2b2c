<?php
return [
    //对接权限系统以及单点登录功能需要的配置信息
    'qx' => [
        'appId' => '1578346421766329',
        'appSecret' => '45a0556348d7a67cc1ed4fdef05ff652',
        'token' => 'cd2222bd12ab7a3e9e06c69a9a1fc89b',
        'ssoServerUrl' => 'http://test.sso-server.checheng.net',//单点登录地址
        'ssoLoginUrl' => 'http://test.sso.checheng.net',//跳转的单点登录页面
        'apiUrl' => 'http://test.qx-api.checheng.net/api',//拉取组织和人员以及角色信息的接口地址
        'modifyPasswordUrl' => 'http://test.sso.checheng.net/profile/password.php',//
    ],
    //阿里云相关信息配置
    'aliyun' => [
        'accessKeyId' => 'urKlSnNL9gjEtUpN',
        'accessKeySecret' => 'OIt1LFBXfscnBANrWNJM8i9wpQFNR7',
        'ossEndpoint' => 'http://oss-cn-shanghai.aliyuncs.com',
        'bucket' => 'chechengupload',
        'cdnDomain' => 'img.che.com',
    ],
    // 高德地图
    'amap' => [
        'key' => 'bb0c1fdbc560d02eb211c195aba8a96b'
    ]
];
