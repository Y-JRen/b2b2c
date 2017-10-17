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

                        layer.confirm("确定取消吗？", {
                            btn: ["是","否"] //按钮
                        }, function(){
                            $.post("'.yii::$app->urlManager->createUrl('store/delete-spu-item-store').'",{id:'.$data->id.'},function(data){
                                if(data.errCode == 0){
                                    layer.msg("取消成功");
                                    loadStore();
                                }else{
                                    layer.msg(data.errMsg);
                                }
                            });
                        }, function(){

                        });
                        ']);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
<?php Pjax::end(); ?>