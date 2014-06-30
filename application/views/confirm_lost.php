<script type="text/javascript">
function getReply(form_id, reply_id) {
    form = document.getElementById(form_id);
    reply  = document.getElementById('reply');
    input_reply = document.getElementById(reply_id);
    r = reply.value;
    input_reply.value = r;
    form.submit();

}
</script>

<h2>处理挂失</h2>
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
            
            <td> <textarea  rows='3' cols='20'name='reply' id ='reply' ></textarea></td>
            <td>
                <form id="confirm_lost_<?=$user['funds_account']?>" method="post" action="" >
                    <input type='hidden'  id='irc_<?=$user['funds_account']?>' name='reply' >
                    <input type='hidden' value='<?=$user['funds_account']?>' name='id' >                
                    <input type='hidden' value='<?=$user['funds_account']?>' name='confirm' >
                </form>
                <button type='submit' class="uk-button confirm" onclick='getReply("confirm_lost_<?=$user['funds_account']?>", "irc_<?=$user['funds_account']?>")' id="confirm_button"  name='confirm'>确认</button>    
                <form id="delete_lost_<?=$user['funds_account']?>" method="post" action="" >
                    <input type='hidden'  id='ird_<?=$user['funds_account']?>' name='reply' >
                    <input type='hidden' value='<?=$user['funds_account']?>' name='id' >
                    <input type='hidden' value='<?=$user['funds_account']?>' name='delete' >
                </form>
                <button class="uk-button delete" onclick='getReply("delete_lost_<?=$user['funds_account']?>","ird_<?=$user['funds_account']?>")' id="delete_button"  name='delete'>拒绝</button>    
            </td>
            
        </tr>
        <?php endforeach;?>
    </tbody>
</table>