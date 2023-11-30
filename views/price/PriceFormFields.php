<?php

use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

?>
<table class="table">
    <thead>
    <th>Название поля</th>
    <th>Значение поля</th>
    <th>Правила обработки</th>
    </thead>
    <tbody>
    <?php foreach ($fieldInstances as $field_key=>$field): ?>
        <tr id="base_field_tr_<?php print $field_key;?>">
            <td><?= $field;?></td>
            <td>
                <?= Html::activeTextInput($model, 'fields[base_fields]['.$field_key.'][name]',['class'=>'form-control']); ?>
            </td>
            <td>

                <?= Html::a((FAS::icon('edit'))." Редактировать", ['/pricer/price/7/edit-rules','field_key'=>$field_key],['class' => 'edit-rule-link btn btn-outline-primary','id'=>'add-rule-'.$field_key]) ?>
            </td>
        </tr>
    <?php endforeach;?>
    <?php foreach ($customFields as $field_key=>$field): ?>
        <tr id="custom_field_tr_<?php print $field_key;?>">
            <td>
                <div class="input-group mb-3">
                    <?= Html::activeTextInput($model, 'fields[custom_fields]['.$field_key.'][name]',['class'=>'form-control']); ?>
                    <?= Html::button(FAS::icon('minus'), ['class' => 'btn btn-outline-danger','id'=>'delete-field-'.$field_key,'onclick'=>'priceFormDeleteField(\''.$field_key.'\');']) ?>
                </div>

            </td>
            <td>
                <?= Html::activeTextInput($model, 'fields[custom_fields]['.$field_key.'][field]',['class'=>'form-control']); ?>
            </td>
            <td>
                <?= Html::a((FAS::icon('edit'))." Редактировать", ['/pricer/price/7/edit-rules','field_key'=>$field_key],['class' => 'edit-rule-link btn btn-outline-primary','id'=>'add-rule-'.$field_key]) ?>
                </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php

$script = <<< JS
//QUICK CREARE CONTACT MODEL
$(document).on('click', '.edit-rule-link', function (event) {
    var url=$(this).attr('href');
    $('#modal').modal('show').find('.modal-body').load(url);
    event.preventDefault();
    
});

JS;
$this->registerJs($script);
?>
