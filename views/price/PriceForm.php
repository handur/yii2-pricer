<?php

use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use app\modules\pricer\assets\Asset;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\pricer\models\Price $model */
/** @var ActiveForm $form */
Asset::register($this);
$title = $op=='edit'?'Редактирование '.$model['name']:'Добавление прайс-листа';
?>

<div class="ShipperForm">
    <h1><?= Html::encode($title) ?></h1>
    <?php $form = ActiveForm::begin(['id'=>'price-edit-form']); ?>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Основные настройки
                </div>
                <div class="card-body">
                    <div class="mb-3"><?= $form->field($model, 'name')->label('Название прайса') ?></div>
                    <div class="mb-3"><?= $form->field($model, 'shipper_id')->dropDownList($shippersList,['prompt'=>'- Укажите поставщика -'])->label('Поставщик') ?></div>
                    <div class="mb-3"><?= $form->field($model, 'product_type')->dropDownList($productTypes,['prompt'=>'- Выберите тип -'])->label('Тип товара') ?></div>
                    <div class="mb-3"><?= $form->field($model, 'active',['template' => '{input} {label} {error}'])->checkbox(['value' => '1'],false)->label('Активен') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Метод загрузки
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <?= $form->field($model, 'settings[load_method]')->dropDownList($loadMethodList,['prompt'=>'- Выберите метод -'])->label('Метод загрузки прайса') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'settings[time_period]')->dropDownList([15=>'Каждые 15 минут',30=>'Каждые 30 минут',60=>'Каждый час',120=>'Каждые 2 часа'],['prompt'=>'- Укажите период -'])->label('Период обновления') ?>
                        </div>
                    </div>
                    <div class="url-lm-wrapper mb-3">
                        <?= $form->field($model, 'settings[load_method_settings][url_method][url]')->label('URL для загрузки') ?>
                    </div>
                    <div class="email-lm-wrapper mb-3">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <?= $form->field($model, 'settings[load_method_settings][email_method][email]')->label('EMAIL для прайсов') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'settings[load_method_settings][email_method][email_from]')->label('EMAIL поставщика') ?>
                            </div>
                        </div>
                        <?= $form->field($model, 'settings[load_method_settings][email_method][email_subject]')->label('SUBJECT письма содержит:') ?>
                        <?= $form->field($model, 'settings[load_method_settings][email_method][email_filename]')->label('Имя файла в письме содержит:') ?>
                    </div>
                    <div class="load-method-test-wrapper d-flex align-items-start">
                        <?= Html::button('Test', ['class' => 'btn btn-primary text-nowrap me-2','name'=>'load-method-test-button']) ?>
                        <div class="load-method-test-result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            Метод чтения
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'settings[read_method]')->dropDownList($readMethodList,['prompt'=>'- Выберите метод -'])->label('Метод чтения прайса') ?>
                </div>
                <div class="col-md-8 offset-md-1">
                    <div class="xml-rm-wrapper mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <?= $form->field($model, 'settings[read_method_settings][xml_method][xquery_product]')->label('Xquery к товару') ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'settings[read_method_settings][xml_method][params_product]')->dropDownList(['attr'=>'Аттрибуты','tags'=>'Вложенные теги'],['prompt'=>'- Выберите метод -'])->label('Тип описания') ?>
                                </div>
                            </div>
                    </div>
                    <div class="csv-rm-wrapper mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'settings[read_method_settings][csv_method][skip_rows]')->label('Пропустить строк (кол-во)') ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'settings[read_method_settings][csv_method][csv_separator]')->label('Разделитель колонок') ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'settings[read_method_settings][csv_method][csv_enclosure]')->label('Экранирование полей') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="read-method-test-wrapper d-flex-col align-items-start">
                <?= Html::button('Test', ['class' => 'btn btn-primary text-nowrap me-2','name'=>'read-method-test-button']) ?>
                <div class="read-method-test-result"></div>
            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Настройки полей
        </div>
        <div class="card-body">
            <div id="product_fields">
                <?php
                print $this->context->renderPartial('PriceFormFields',['model'=>$model,'fieldInstances'=>$fieldInstances,'customFields'=>$customFields]);
                ?>
            </div>
            <?= Html::button((FAS::icon('plus')).' Добавить поле', ['class' => 'btn btn-success','id'=>'add-field']) ?>
        </div>
    </div>
    <div class="mt-3 form-actions form-group"><div class="text-end"><?= Html::submitInput('Сохранить', ['class' => 'btn btn-success btn-lg','name'=>'price-form-submit']) ?></div></div>
    <?= $form->field($model, 'id')->hiddenInput(['value'=> $model->id])->label(false);?>
    <?php ActiveForm::end(); ?>
</div><!-- ShipperForm -->
<?php
Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'size' => 'modal-lg',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
Modal::end();
?>

<script>
    var $form = $('#price-edit-form');
    function priceFormDeleteField(field_key){
        $.ajax({
            url: "/pricer/price/delete-product-field/"+field_key,
            data: $form.serialize(),
            type: "POST",
            success: function(data){
                $('#product_fields').html(data);
            }
        });
    }

    $("button#add-field").click(function(){
        $.ajax({
            url: "/pricer/price/add-product-fields",
            data: $form.serialize(),
            type: "POST",
            success: function(data){
                $('#product_fields').html(data);
            }
        });
    });
    $("select#price-product_type").change(function(){
        $.ajax({
            url: "/pricer/price/get-product-fields",
            data: $form.serialize(),
            //data: {"op":"refreshFields", "product_type": $(this).val(), "price_id": <?php print $model->id;?> },
            type: "POST",
            success: function(data){
                $('#product_fields').html(data);
            }
        });
    });
    $('button[name=load-method-test-button]').on('click',function(){
        var button=$(this);
        var data = $form.serialize();
        $.ajax({
            url: '/pricer/test/load',
            type: 'POST',
            data: data,
            beforeSend: function (jqXHR){
                button.prepend('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');

            },
            success: function (data) {
                button.find('.spinner-border').remove();
                $('.load-method-test-result').html('<div class="test-result alert alert-primary"  role="alert">'+data+'</div>');
            },
            error: function(jqXHR, errMsg) {
                button.find('.spinner-border').remove();
                $('.load-method-test-result').html('<div class="test-result alert alert-danger"  role="alert">'+jqXHR.responseText+'</div>');
            }
        });
        return false;
    });
    $('button[name=read-method-test-button]').on('click',function(){
        var button=$(this);
        var data = $form.serialize();
        $.ajax({
            url: '/pricer/test/read',
            type: 'POST',
            data: data,
            beforeSend: function (jqXHR){
                button.prepend('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
            },
            success: function (data) {
                button.find('.spinner-border').remove();
                $('.read-method-test-result').html('<div class="test-result alert alert-light"  role="alert">'+data+'</div>');
            },
            error: function(jqXHR, errMsg) {
                button.find('.spinner-border').remove();
                $('.read-method-test-result').html('<div class="test-result alert alert-danger"  role="alert">'+jqXHR.responseText+'</div>');
            }
        });
        return false;
    });
</script>