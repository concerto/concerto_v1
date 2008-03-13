<?php if(isAdmin()) { ?>
<p><a href="<?=ADMIN_URL.'/groups/new'?>">Add new group</a></p>
<? } ?>
<h2>Click on a group for more information.</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
if(is_array($this->groups))
foreach($this->groups as $groupid => $group){
   ?>
<tr>
   <td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
   <a href="<?= ADMIN_URL?>/groups/show/<? echo $groupid ?>">
   <h1><?= $group[name] ?></h1>
   </a>
      <p><?= $group[members] ?> member<?=$group[members]!=1?"s":""?></p>
      <?php if(is_array($group[controls]))
        echo "<p>Controls ".join(" and ", $group[controls]).'</p>';
      ?>
   </td>
</tr>

<?php
}
?>
</table>