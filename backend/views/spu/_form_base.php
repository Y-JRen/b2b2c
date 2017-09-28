<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */
/* @var $form common\widgets\ActiveForm */
?>

<div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">基本信息</a></li>
            <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">商品介绍</a></li>
            <li class=""><a href="#settings" data-toggle="tab" aria-expanded="false">提车地点</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="activity">
                <div class="spu-form-form">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="box-body table-responsive">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputName" class="col-sm-3 control-label">所属商户:</label>
    
                                <div class="col-sm-9">
                                    <p class="form-control" style="border: none">名字</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputName" class="col-sm-3 control-label">所属ID:</label>
    
                                <div class="col-sm-9">
                                    <p class="form-control" style="border: none"><?=$model->partner_id?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputName" class="col-sm-3 control-label">商品类型:</label>

                                <div class="col-sm-9">
                                    <p class="form-control" style="border: none">
                                        <?= \common\models\SkuSpuType::findOne($model->type_id)->name ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-2">
                                <p class="form-control" style="background: #d3d3d3"><?=$model->brand_name?></p>
                            </div>
                            <div class="form-group col-md-2">
                                <p class="form-control" style="background: #d3d3d3"><?=$model->brand_name?></p>
                            </div>
                            <div class="form-group col-md-2">
                                <p class="form-control" style="background: #d3d3d3"><?=$model->brand_name?></p>
                            </div>
                            <div class="form-group col-md-2">
                                <p class="form-control" style="background: #d3d3d3"><?=$model->brand_name?></p>
                            </div>
                        </div>
            
                    </div>
                    <div class="box-footer">
                        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

<?php

$script = <<<_SCRIPT
    function changeValue(current, next, url) {
        
        if(current == "spuform-brand_id"){
            $("#"+next).html('<option>厂商</option>')
            $("#spuform-series_id").html('<option>车系</option>')
            $("#spuform-car_id").html('<option>车型</option>')
        } else if (current == "spuform-factory_id") {
            $("#spuform-series_id").html('<option>车系</option>')
            $("#spuform-car_idd").html('<option>车型</option>')
        } else {
            $("#spuform-car_id").html('<option>车型</option>')
        }
        if(next == "spuform-factory_id") {
            var html = '<option>厂商</option>';
        } else if(next == "spuform-series_id") {
            var html = '<option>车系</option>';
        } else {
            var html = '<option>车型</option>';
        }
        $.get(url, function(data){
            for (var i = 0; i < data.length; i++){
                html += "<option value="+data[i]['key']+">"+data[i]['value']+"</option>"
            }
            $("#"+next).html(html)
        },"json");
    }
    $("#spuform-brand_id").change(function(){
        changeValue("spuform-brand_id", 'spuform-factory_id', "/car/brand?brand_id="+$(this).val())
    });
    $("#spuform-factory_id").change(function(){
        changeValue("spuform-factory_id", 'spuform-series_id', "/car/series?factory_id="+$(this).val())
    });
    $("#spuform-series_id").change(function(){
        changeValue("spuForm-series_id", 'spuform-car_id', "/car/car?car_type_id="+$(this).val())
    });
    
    
_SCRIPT;

$this->registerJs($script);?>
            
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                    <!-- timeline time label -->
                    <li class="time-label">
                    <span class="bg-red">
                      10 Feb. 2014
                    </span>
                    </li>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <li>
                        <i class="fa fa-envelope bg-blue"></i>

                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                            <h3 class="timeline-header"><a href="#">支持团队</a> 给你发了一封电子邮件</h3>

                            <div class="timeline-body">
                                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                quora plaxo ideeli hulu weebly balihoo...
                            </div>
                            <div class="timeline-footer">
                                <a class="btn btn-primary btn-xs">读更多</a>
                                <a class="btn btn-danger btn-xs">删除</a>
                            </div>
                        </div>
                    </li>
                    <!-- END timeline item -->
                    <!-- timeline item -->
                    <li>
                        <i class="fa fa-user bg-aqua"></i>

                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                            <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> 接受你的朋友的请求t
                            </h3>
                        </div>
                    </li>
                    <!-- END timeline item -->
                    <!-- timeline item -->
                    <li>
                        <i class="fa fa-comments bg-yellow"></i>

                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                            <h3 class="timeline-header"><a href="#">Jay White</a> 评论你的帖子</h3>

                            <div class="timeline-body">
                                Take me to your leader!
                                Switzerland is small and neutral!
                                We are more like Germany, ambitious and misunderstood!
                            </div>
                            <div class="timeline-footer">
                                <a class="btn btn-warning btn-flat btn-xs">查看评论</a>
                            </div>
                        </div>
                    </li>
                    <!-- END timeline item -->
                    <!-- timeline time label -->
                    <li class="time-label">
                    <span class="bg-green">
                      3 Jan. 2014
                    </span>
                    </li>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <li>
                        <i class="fa fa-camera bg-purple"></i>

                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                            <h3 class="timeline-header"><a href="#">Mina Lee</a> 上传了新的照片</h3>

                            <div class="timeline-body">
                                <img src="http://placehold.it/150x100" alt="..." class="margin">
                                <img src="http://placehold.it/150x100" alt="..." class="margin">
                                <img src="http://placehold.it/150x100" alt="..." class="margin">
                                <img src="http://placehold.it/150x100" alt="..." class="margin">
                            </div>
                        </div>
                    </li>
                    <!-- END timeline item -->
                    <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                </ul>
            </div>
            <!-- /.tab-pane -->

            <div class="tab-pane" id="settings">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">名字</label>

                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputName" placeholder="名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">邮箱</label>

                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail" placeholder="邮箱">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">名字</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName" placeholder="名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">经验</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="inputExperience" placeholder="经验"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSkills" class="col-sm-2 control-label">技能</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputSkills" placeholder="技能">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> 我同意 <a href="#">条款和条件</a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-danger">提交</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- /.nav-tabs-custom -->
</div>
