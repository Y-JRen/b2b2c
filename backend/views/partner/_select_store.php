<div class="modal-dialog " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
            <h4 class="modal-title">选择可用门店</h4>
        </div>
        <div class="modal-body">
            <form method="post" class="form-horizontal" name="edit-form" id="edit-form-info">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-3 contridl-label"> 可用门店 </label>
                        <div class="col-sm-9">
                            <select name="store_id" id="select-store-id" class="form-control" required="true">
                                <?php if ($store): ?>
                                    <?php foreach ($store as $value) : ?>
                                        <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary btn-image " id="me-table-store-save-user">确定</button>
        </div>
    </div>
</div>