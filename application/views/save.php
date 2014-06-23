<h2>存款</h2>
<hr class="uk-article-divider"/>
<?php if( strlen($info) != 0 ) echo $info.'<hr class="uk-article-divider"/>'; ?>
<form method="post" action="" >
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
    金额：<input type="text" name="amount" <?php if( strlen($old_amount) != 0 ) echo 'value="'.$old_amount.'"'; ?> />
    <br><br>
    <input type="submit" value="存款">
    </form>