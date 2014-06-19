<h2>查看交易记录</h2>
<hr class="uk-article-divider"/>
    <form id="get_log" method="post" action="" >
        资金账户号码：<input type="text" name="id" <?php if( strlen($old_id) != 0 ) echo 'value="'.$old_id.'"'; ?> />
        <br><br>
        币种：
        <select name="currency">
            <option value="" <?php if( strlen($old_currency) == 0 ) echo 'selected="selected"'; ?> >不限</option>
            <option value="CNY" <?php if( $old_currency == 'CNY' ) echo 'selected="selected"'; ?> >CNY</option>
            <option value="USD" <?php if( $old_currency == 'USD' ) echo 'selected="selected"'; ?> >USD</option>
            <option value="EUR" <?php if( $old_currency == 'EUR' ) echo 'selected="selected"'; ?> >EUR</option>
            <option value="JPY" <?php if( $old_currency == 'JPY' ) echo 'selected="selected"'; ?> >JPY</option>
            <option value="HKD" <?php if( $old_currency == 'HKD' ) echo 'selected="selected"'; ?> >HKD</option>
            <option value="GBP" <?php if( $old_currency == 'GBP' ) echo 'selected="selected"'; ?> >GBP</option>
            <option value="CAD" <?php if( $old_currency == 'CAD' ) echo 'selected="selected"'; ?> >CAD</option>
            <option value="AUD" <?php if( $old_currency == 'AUD' ) echo 'selected="selected"'; ?> >AUD</option>
            <option value="CHF" <?php if( $old_currency == 'CHF' ) echo 'selected="selected"'; ?> >CHF</option>
            <option value="SGD" <?php if( $old_currency == 'SGD' ) echo 'selected="selected"'; ?> >SGD</option>
        </select>
        <br><br>
        时间：
        <input type="date" name="date" <?php if( strlen($old_date) != 0 ) echo 'value="'.$old_date.'"'; ?>/>
        <br><br>
        不限<input type="radio" <?php if( $old_increase == 'both' ) echo 'checked="checked"'; ?> name="increase" value="both" />
        &nbsp&nbsp&nbsp转入<input type="radio" <?php if( $old_increase == 'increase' ) echo 'checked="checked"'; ?> name="increase" value="increase" />
        &nbsp&nbsp&nbsp转出<input type="radio"  <?php if( $old_increase == 'decrease' ) echo 'checked="checked"'; ?> name="increase" value="decrease" />
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
            <td><?=$log['funds_account']?></td>
            <td><?=$log['currency']?></td> 
            <td><?= $log['amount'] > 0 ? '+'.(string)$log['amount'] : (string)$log['amount'] ?></td>
            <td><?=$log['balance']?></td>
            <td><?=$log['time']?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>