<h2>Please use these utilities Wisely.


<h3>Admin Privs: <span class="emph"><?= isAdmin() ?></span></h3>

<h3>Reset Session: <span class="emph"><a href="<?=ADMIN_URL?>/frontpage/su?r=1">reset</a></span></h3>
<h3>Masquerade (su)</h3>
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
<h3>Session:</h3>

<pre>
<? print_r($_SESSION) ?>
</pre>
<h3>PHP Info</h3>
<div>
<? phpinfo() ?>
</div>