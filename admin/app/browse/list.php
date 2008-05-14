<h2>Click on a type of content to show from a particular feed.</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
foreach($this->feeds as $feed) {
    if(!$feed->user_priv($_SESSION["user"])) continue;
    $types = $feed->get_types();
    if($types == false) continue;
?>
<tr>
    <td>
      <h1><?= htmlspecialchars($feed->name) ?></h1>
      <?php
      $list = array();
      foreach($types as $type_id => $type_name) {
          $list[] = "<span class=\"emph\"><a href=\"".ADMIN_URL."/browse/show/{$feed->id}/type/$type_id\">$type_name</a></span>";
      }
      echo join($list, ", ");
      ?>
    </td>
    <td>
      <h4><? $group = new Group($feed->group_id) ?><a href="<?= ADMIN_URL ?>/groups/show/<?= $group->id ?>"><?= $group->name ?></a></h4>
    </td>
</tr>
<? } ?>
</table>
