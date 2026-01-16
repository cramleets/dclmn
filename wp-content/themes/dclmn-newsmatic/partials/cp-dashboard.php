<?php if (!dclmn_auth('cp')): ?>
  <?php

  if (!empty($_SESSION) && $_SESSION['dclmn_cp_message'] || !empty($_GET['msg'])) {
    $msg = ($_SESSION['dclmn_cp_message']) ?: $_GET['msg'];
    $extra_content = $msg;
    unset($_SESSION['dclmn_cp_message']);
    echo '<div class="session-login-error">'. $extra_content .'</div>';
  }
  ?>
  <form method="post" id="cp-login-form">
    <p>To log in as a DCLMN Committee Person, please enter your email address.</p>
    <input type="text" name="email" id="cp-email">
    <input type="submit" value="go" id="cp-login-trigger" class="button">
    <div id="cp-login-result"></div>
  </form>
  <script>
    jQuery('#cp-email').focus();
  </script>
<?php else: ?>
  Options
  <hr>
  <a href="<?php echo home_url('cp/?action=cp-logout&cb='. uniqid()); ?>" onclick="return confirm('Are you sure?');">Log Out</a>
<?php endif; ?>