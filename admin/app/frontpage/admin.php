<h2>Please use these utilities wisely.</h2>

<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
  <h1>Masquerade (su)</h1>
  <form action="<?=ADMIN_URL?>/frontpage/su" method="POST">
    <select name="su">
    <option></option>
<?php
      $userids = sql_select("user","username",false,"ORDER BY username");
      $this->users=Array();
      if(is_array($userids))
         foreach($userids as $user) {
            $user = new User($user[username]);
            echo '<option value="'.$user->username.'">'.$user->username.' - '.$user->name.'</option>';
         }

?>
    </select>
    <input type="submit" value="su" />
  </form>
  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>
<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
  <h1>Page Load Statisics</h1>
  <form method="POST">
    <input type="submit" name="stats" value="Turn <?=$_SESSION['stats']?'Off':'On'?>" />
  </form>
  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>


<h3>Admin Privs: <span class="emph"><?= isAdmin() ?></span></h3>
<h3>Reset Session: <span class="emph"><a href="<?=ADMIN_URL?>/frontpage/su?r=1">reset</a></span></h3>
<a href="<?= ADMIN_URL ?>/frontpage/phpinfo">PHP Info</a>