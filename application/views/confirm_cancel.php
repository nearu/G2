<h2>处理注销</h2>
<hr class="uk-article-divider"/>
<table class="uk-table uk-table-hover" id="table">
    <thead>
        <tr>
            <th>编号</th>
            <th>时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?=$user['funds_account']?></td>
            <td><?=$user['time']?></td> 
            <td>
                <form id="confirm_cancel" method="post" action="<?=base_url('index.php/admin/confirm_cancel')?>" >
                    <input type='text' value='<?=$user['funds_account']?>' name='xxxx' >
                    <input type='hidden' value='<?=$user['funds_account']?>' name='id' >
                    <input type='submit' class="uk-button confirm" id="<?=$user['funds_account']?>" value='确认' name='confirm'>    
                </form>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>