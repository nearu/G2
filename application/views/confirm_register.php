<h2>处理开户</h2>
<hr class="uk-article-divider"/>
<table class="uk-table uk-table-hover" id="table">
    <thead>
        <tr>
            <th>编号</th>
            <th>证券账户号</th>
            <th>客户姓名</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?=$user['id']?></td>
            <td><?=$user['stock_account']?></td> 
            <td><?=$user['customer_name']?></td>
            <td>
                <form id="confirm_register" method="post" action="" >
                    <input type='hidden' value='<?=$user['id']?>' name='id' >
                    <input type='submit' class="uk-button confirm" id="<?=$user['id']?>" value='确认' name='confirm'>    
                </form>
                <!--
                <form id="delete_register" method="post" action="" >
                    <input type='hidden' value='<?=$user['id']?>' name='id' >
                    <input type='submit' class="uk-button delete"  id="<?=$user['id']?>"  value='删除'  name='delete'>
                </form>
            -->
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>