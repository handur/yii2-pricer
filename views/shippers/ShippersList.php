<?php
    use yii\helpers\Html;
?>
<div class="shippers-list">
    <h1>Список поставщиков</h1>
    <div class="actions mb-3">
        <a href="/pricer/shippers/add" class="btn btn-primary btn-lg">+ Добавить нового поставщика</a>
    </div>

    <table class="table table-bordered">

    <?php foreach($shippers as $item):?>
        <tr>
            <td>
                <?php print $item->name;?> (<?php print $item->machine_key;?>)
            </td>
            <td>
                <?php if($item->active==1):?>
                    <span class="badge bg-success rounded-pill">active</span>
                <?php else:?>
                    <span class="badge bg-secondary rounded-pill">disabled</span>
                <?php endif;?>
            </td>
            <td>
                <span class="badge rounded-pill text-bg-light">Прайс-листов: <?php print count($item->prices);?></span>
            </td>
            <td>
                <ul class="list-inline text-end mb-0">
                    <li class="list-inline-item">
                        <?php echo Html::a('Добавить прайс-лист',['/pricer/price/add', 'shipper_id' => $item->id], [
                            'class' => 'btn btn-outline-primary btn-sm'
                        ]) ?>
                    </li>
                    <li class="list-inline-item">
                        <?php echo Html::a('Редактировать поставщика',['edit', 'id' => $item->id], [
                            'class' => 'btn btn-outline-primary btn-sm'
                        ]) ?>
                    </li>
                    <li class="list-inline-item">
                        <?php echo Html::a('Удалить',['delete', 'id' => $item->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </li>
                </ul>
            </td>
        </tr>

    <?php endforeach;?>
    </table>
</div>