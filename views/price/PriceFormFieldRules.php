<?php

use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
?>
<?php $form = ActiveForm::begin([ 'enableClientValidation' => true,
    'options'                => [
        'id'      => 'dynamic-form'
    ]]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Add Comment</h4>
</div>
<div class="modal-body">
    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
</div>
<div class="modal-footer">
    <?php echo Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = <<< JS

   $(document).ready(function () { 
        $("#form-add-contact").on('beforeSubmit', function (event) { 
            event.preventDefault();            
            var form_data = new FormData($('#form-add-contact')[0]);
            $.ajax({
                   url: $("#form-add-contact").attr('action'), 
                   dataType: 'JSON',  
                   cache: false,
                   contentType: false,
                   processData: false,
                   data: form_data, //$(this).serialize(),                      
                   type: 'post',                        
                   beforeSend: function() {
                   },
                   success: function(response){                      
                       toastr.success("",response.message); 
                       $('#addContactFormModel').modal('hide');
                   },
                   complete: function() {
                   },
                   error: function (data) {
                      toastr.warning("","There may a error on uploading. Try again later");    
                   }
                });                
            return false;
        });
    });       

JS;
$this->registerJs($script);
?>