<?php

use yii\helpers\Html;
use common\widgets\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Spu */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(['enablePushState'=>false]); ?>

<div class="spu-form-index">
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => '门店名称',
                    'value' => function($model){
                        return $model->store->name;
                    }
                ],
                [
                    'label' => '门店地址',
                    'value' => function($model){
                        return $model->store->address;
                    }
                ],
                [
                    'label' => '联系人',
                    'value' => function($model){
                        return $model->store->contact_person;
                    }
                ],
                [
                    'label' => '联系电话',
                    'value' => function($model){
                        return $model->store->contact_phone;
                    }
                ],
                [
                    'label' => '操作',
                    'format' => 'raw',
                    'value' => function($data) {
                        $html = Html::a('取消选择', 'javascript:void(0);',['onclick'=>'
                        $.post("'.yii::$app->urlManager->createUrl('store/delete-spu-item-store').'",{id:'.$data->id.'},function(data){
                            if(data.errCode == 0){
                                loadStore();
                            }else{
                                alert(data.errMsg);
                            }
                        });']);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
<?php Pjax::end(); ?>