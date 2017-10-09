<?php

use yii\helpers\Html;
use common\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Spu */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spu-form-index">
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => '门店名称',
                    'value' => 'name'
                ],
                [
                    'label' => '所在地区',
                    'value' => function($model){
                        return $model->province_name. ' '. $model->city_name. ' '.$model->area_name;
                    }
                ],
                'address',
                'contact_person',
                'contact_phone',
                [
                    'label' => '操作',
                    'format' => 'raw',
                    'value' => function($data) {
                        $html = Html::a('取消选择', ['update', 'id' => $data->id]);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
