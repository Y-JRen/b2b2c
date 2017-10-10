<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */
/* @var $image_model \common\models\SkuItemAttachment */

$this->title = '编辑基本信息: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="spu-form-update">
    
    <?php
    if($model->item_type_id == 1) {
        //普通车
        echo $this->render('_form_base', [
            'model' => $model,
            'image_model' => $image_model,
        ]) ;
    } else {
        //融资租凭
        echo $this->render('_form_lease', [
            'model' => $model,
        ]) ;
    }
    ?>

</div>
