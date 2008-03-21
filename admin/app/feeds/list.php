<?php if(isAdmin()) { ?>
<p><a href="<?=ADMIN_URL.'/feeds/new'?>">Add new feed</a></p>
<? } ?>

<h2>Click on a feed for more information and contents.</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
foreach($this->feeds as $feed){
?>
  <tr>
    <td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
    <a href="<?= ADMIN_URL?>/feeds/show/<?= $feed->id ?>">
    <h1><?= $feed->name ?></h1>
    </a>
    </td>
  </tr>

<?php
}
?>
</table>