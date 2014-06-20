<h2>查询账户</h2>
<hr class="uk-article-divider"/>
<label>客户姓名：</label><?=$acc['customer_name']?>
</br>
<label>证券账户：</label><?=$acc['stock_account']?>
</br>
<label>资金账户：</label><?=$acc['id']?>

<table class="uk-table uk-table-hover" id="table">
    <thead>
        <tr>
            <th>币种</th>
            <th>余额</th>
            <th>冻结金额</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($curs as $cur): ?>
        <tr>
            <td><?=$cur['currency_type']?></td>
            <td><?=$cur['balance']?></td> 
            <td><?=$cur['frozen_balance']?></td> 
        </tr>
    <?php endforeach;?>
    </tbody>
</table>