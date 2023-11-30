<?php
use yii\helpers\Html;
?>
<div class="shippers-list">
    <h1>Список прайс-листов</h1>
    <div class="actions mb-3">
        <a href="/pricer/prices/add" class="btn btn-primary btn-lg">+ Добавить новый прайс-лист</a>
    </div>
    <ul class="list-group ">

        <?php foreach($prices as $item):?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold"><?php print $item->name;?></div>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <?php if($item->active==1):?>
                                <span class="badge bg-success rounded-pill">active</span>
                            <?php else:?>
                                <span class="badge bg-secondary rounded-pill">disabled</span>
                            <?php endif;?>
                        </li>
                    </ul>
                </div>
                <div class="nav-links">
                    <ul class="nav">

                        <li class="nav-item">
                            <?php echo Html::a('Редактировать',['edit', 'id' => $item->id], [
                                'class' => 'nav-link'
                            ]) ?>
                        </li>
                        <li class="nav-item">
                            <?php echo Html::a('Удалить',['delete', 'id' => $item->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </li>
                    </ul>
                </div>

            </li>

        <?php endforeach;?>

    </ul>
</div>