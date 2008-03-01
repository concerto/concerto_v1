<p><a href="<?=ADMIN_URL.'/contents/new'?>">Submit Content</a></p>

<h2>All content in the system is shown.  Click on a title for details.</h2>
<?php
foreach(array_keys($this->contents) as $field)
     $urls[]='<a href="#'.$field.'">'.$field.'</a>';
?>
<p>Jump to: <?=join(" | ", $urls)?>
</p>
<?php
foreach($this->contents as $field=>$contents)
{
   echo "<a name=\"$field\" /><h1>$field</h1>";
?>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
   $notfirst=0; //style for first row
   foreach($contents as $content) {
      $submitter = sql_select('user', Array('name','username'), "id = $content->user_id");
      $submitter = $submitter[0];
?>
  <tr>
<?php
      if(preg_match('/image/',$content->mime_type)) {
        $has_imagecol=1;
?>
    <td<? if (!$notfirst) echo ' class="firstrow"'; ?>>
    <a href="<?= ADMIN_URL?>/content/show/<?= $content->id ?>"> 
    <img src="<?= ADMIN_URL?>/content/image/<?= $content->id ?>?width=200&height=150" />
<!--        <img src="http://signage.union.rpi.edu/upload/minimage.php?source=/var/www/ds/content/24.jpg&scale=0.25&type=1" /> -->
    </a>
    </td>
<?php
      }
?>

    <td class="editcol<? if (!$notfirst) {$notfirst =1;  echo ' firstrow';} ?>"
        <?if(!$has_imagecol) echo "colspan=2";?>>
      <a href="<?= ADMIN_URL?>/contents/show/<? echo $content->id ?>">
       <h2><img src='$stat' style='border:0px;' border='0' alt='' /></h2>
       <h1><a href="<?= ADMIN_URL?>/contents/show/<? echo $content->id ?>"><?=$content->name?></a></h1>
       <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;">
<?php
          if($content->mime_type == "text/plain")
             echo "$content->content<br/>\n";
?>
       <?=date("m/j/Y",strtotime($content->start_date))?> - <?=date("m/j/Y",strtotime($content->end_date))?></span>
       <h2>Submitted by <strong><a href="<?=ADMIN_URL.'/users/show/'.$submitter[username]?>"><?=$submitter[name]?></a></strong></h2>
      </a>
    </td>
  </tr>

<?php
   }
?>
</table>
<?php
}
?>
