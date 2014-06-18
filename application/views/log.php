<h2>查看交易记录</h2>
<hr class="uk-article-divider"/>
    <form id="get_log" method="post" action="" >
        资金账户号码：<input type="text" name="id" />
        <br><br>
        币种：
        <select name="currency">
            <option value="" selected="selected">不限</option>
            <option value="CNY">CNY</option>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="JPY">JPY</option>
            <option value="HKD">HKD</option>
            <option value="GBP">GBP</option>
            <option value="CAD">CAD</option>
            <option value="AUD">AUD</option>
            <option value="CHF">CHF</option>
            <option value="SGD">SGD</option>
        </select>
        <br><br>
        时间：
        <input type="date" name="date" />
        <br><br>
        不限<input type="radio" checked="checked" name="increase" value="both" />
        &nbsp&nbsp&nbsp转入<input type="radio" name="increase" value="increase" />
        &nbsp&nbsp&nbsp转出<input type="radio" name="increase" value="decrease" />
        <br><br>
        <input type="submit" value="查找">
    </form>
<hr class="uk-article-divider"/>
<table class="uk-table uk-table-hover" id="table">
    <thead>
        <tr>
            <th>资金账户号</th>
            <th>币种</th>
            <th>金额</th>
            <th>余额</th>
            <th>时间</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($logs as $log): ?>
        <tr>
            <td><?=$log['funds_account_number']?></td>
            <td><?=$log['currency']?></td> 
            <td><?= $log['amount'] > 0 ? '+'.(string)$log['amount'] : (string)$log['amount'] ?></td>
            <td><?=$log['balance']?></td>
            <td><?=$log['time']?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>