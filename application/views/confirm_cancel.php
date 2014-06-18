<h2>处理注销</h2>
<hr class="uk-article-divider"/>
<table class="uk-table uk-table-hover" id="table">
    <thead>
        <tr>
            <th>编号</th>
            <th>时间</th>
            <th>回复</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?=$user['funds_account']?></td>
            <td><?=$user['time']?></td> 
            <form id="confirm_lost" method="post" action="" >
            <td> <input type='textarea'  name='reply' ></td>
            <td>
                    <input type='hidden' value='<?=$user['funds_account']?>' name='id' >
                    <input type='submit' class="uk-button confirm" id="<?=$user['funds_account']?>" value='确认' name='confirm'>    
            </td>
            </form>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>