<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
    <form id="login" name="login" method="post" action="<?= ADMIN_URL ?>/frontpage/auth">
      <p>
        <label>Username
          <input name="user[username]" type="text" id="user[username]" size="15" />
        </label>
        <br /><br />
        <label>Password
          <input name="user[password]" type="password" id="user[password]" size="15" />
        </label>
        <br /><br />
        <label>
          <input type="submit" name="Submit" value="Submit" />
        </label>
      </p>
    </form>
  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>