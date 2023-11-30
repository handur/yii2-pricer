<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\pricer\models\Shipper $model */
/** @var ActiveForm $form */

$this->title = $op=='edit'?'Редактирование '.$model['name']:'Добавление поставщика';
?>

<div class="ShipperForm">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="mb-3"><?= $form->field($model, 'name')->label('Название поставщика') ?></div>
    <div class="mb-3"><?= $form->field($model, 'machine_key')->label('Ключ') ?></div>
    <div class="mb-3"><?= $form->field($model, 'active',['template' => '{input} {label} {error}'])->checkbox(['value' => '1'],false)->label('Активен') ?></div>
    <div class="form-actions"><div class="form-group"><?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?></div></div>
    <?php ActiveForm::end(); ?>

</div><!-- ShipperForm -->
