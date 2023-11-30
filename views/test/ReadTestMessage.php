<?php
    $header=[];
    $rows=[];
    foreach($result as $el){
        $header=array_unique(array_merge($header,array_keys($el)));
    }

    foreach($header as $key){
        $row=[$key];
        foreach($result as $el){
            if(isset($el[$key])) $row[]=$el[$key];
            else $row[]='';
        }
        $rows[]=$row;
    }
?>
<table class="table table-bordered table-light small">
    <?php foreach($rows as $row):?>
        <tr>
            <?php foreach($row as $cnum=>$col):?>
                <?php if($cnum==0):?>
                    <th><?php print $col;?></th>
                <?php else :?>
                    <td><?php print $col;?></td>
                <?php endif;?>
            <?php endforeach;?>
        </tr>
    <?php endforeach;?>
</table>
