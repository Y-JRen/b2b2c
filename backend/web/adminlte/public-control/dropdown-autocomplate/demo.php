<?php
    $arr = [
        f=>true,
        data=>[
            [value =>1 ,name=>"广州区"],
            [value=>1,name=>"深圳区"]
        ]
    ];
    $arr1 = [
        f=>true,
        data=>[
            [value =>2 ,name=>"白云店"],
            [value=>2,name=>"天河店"]
        ]
    ];
    //$arr1 = [{value:1,name:"白云店"},{value:1,name:"天河店"}];

    if($_GET){
       $j = $_GET["j"];
    }
    if($j==1){
        echo json_encode($arr);
    }else{
        echo json_encode($arr1);
    }
?>