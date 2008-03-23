<?php if(isAdmin()) { ?>
<a href="<?=ADMIN_URL.'/users/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New User</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<? } ?>
<h2>Click on a user to view their profile.</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
foreach($this->users as $user){
   ?>
<tr>
   <td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
   <h1><a href="<?= ADMIN_URL?>/users/show/<? echo $user->username ?>"><?= $user->name ?></a></h1>
   <?php
     $groups=array();
     if($user->admin_privileges) 
        $groups[]= "<strong>Concerto Administrators</strong>";
     $group_objs=$user->list_groups();
     if(is_array($group_objs))
        foreach($user->list_groups() as $group) 
           $groups[] = '<a href="'.ADMIN_URL."/groups/show/$group->id\">$group->name</a>";
     if(count($groups)>0)
        echo 'Member of: '.join(", ", $groups);
   ?></td>
</tr>

<?php
}
?>
</table>
