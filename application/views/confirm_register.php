<!--

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
                <form id="delete_register" method="post" action="" >
                    <input type='hidden' value='<?=$user['id']?>' name='id' >
                    <input type='submit' class="uk-button delete"  id="<?=$user['id']?>"  value='拒绝'  name='delete'>
                </form>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

-->

<h2>开户</h2>
<hr class="uk-article-divider"/>
<?php if( strlen($info) != 0 ) echo $info.'<hr class="uk-article-divider"/>'; ?>
<form method="post" action="" >
        &nbsp&nbsp&nbsp证券账户号：<input type="text" name="stock_account" <?php if( strlen($old_stock_account) != 0 ) echo 'value="'.$old_stock_account.'"'; ?> />
        <br><br>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp交易密码：<input type="password" name="trade_password" />
        <br><br>
        重复交易密码：<input type="password" name="trade_password1" />
        <br><br>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp取款密码：<input type="password" name="withdraw_password" />
        <br><br>
        重复取款密码：<input type="password" name="withdraw_password1" />
        <br><br>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp客户姓名：<input type="text" name="customer_name" <?php if( strlen($old_customer_name) != 0 ) echo 'value="'.$old_customer_name.'"'; ?> />
        <br><br>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp身份证号：<input type="text" name="id_card_number" <?php if( strlen($old_id_card_number) != 0 ) echo 'value="'.$old_id_card_number.'"'; ?> />
        <br><br>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        <input type="hidden" name="open" value="1" />
        <input type="submit" value="开户" />
    </form>