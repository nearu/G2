<h2>处理挂失</h2>
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
                <form id="confirm_lost" method="post" action="" >
                    <input type='text'  name='reply' >
                    <input type='hidden' value='<?=$user['funds_account']?>' name='id' >
                    <input type='submit' class="uk-button confirm" id="<?=$user['funds_account']?>" value='确认' name='confirm'>    
                </form>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>