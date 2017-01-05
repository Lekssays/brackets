<div class="ui stackable menu">
  <div class="item">
    <img src="brackets-logo-50.png">
  </div>
  <a class="red item" href="index.php">
    <i class="home icon"></i> Home
  </a>
  <?php if ( checkrole('balloon') ) { ?>
    <a class="red item" href="balloons.php">
      <i class="flag icon"></i> Balloons
    </a>
  <?php } ?>
  <?php if ( checkrole('jury') ) { ?>
    <a class="item" href="problems.php">
      <i class="file icon"></i> Problems
    </a>
  <?php } ?>
  <?php if ( checkrole('jury') ) { ?>
    <a class="item" href="contests.php">
      <i class="book icon"></i> Contests
    </a>
  <?php } ?>
  <?php if ( checkrole('jury') ) { ?>
    <a class="item" href="users.php">
      <i class="user icon"></i> Users
    </a>
  <?php } ?>
  <?php if ( checkrole('jury') ) { ?>
    <a class="item" href="teams.php">
      <i class="users icon"></i> Teams
    </a>
  <?php } ?>
  <?php if ( IS_ADMIN ) {
    $ndown = count($updates['judgehosts']);
    if ( $ndown > 0 ) { ?>
      <a class="item" href="judgehosts.php">
        <i class="database icon"></i> Judgehosts (<?php echo $ndown ?> down)
      </a>
  <?php } else { ?>
      <a class="item" href="judgehosts.php">
        <i class="database icon"></i> Judgehosts
      </a>
  <?php }
    $nerr = count($updates['internal_error']);
    if ( $nerr > 0 ) { ?>
      <a class="new" href="internal_errors.php" accesskey="e" id="menu_internal_error"><span class="octicon octicon-zap"></span> Internal Error (<?php echo $nerr ?> new)</a>
      <a class="item" href="internal_errors.php">
        <i class="warning icon"></i> Internal Error (<?php echo $nerr ?> new)
      </a>
    <?php }
  } ?>
  <a class="item" href="submissions.php">
    <i class="plus icon"></i> Submissions
  </a>
  <?php
    $nrejudgings = count($updates['rejudgings']);
    if ( $nrejudgings > 0 ) { ?>
      <a class="item" href="rejudgings.php">
        <i class="repeat icon"></i> Rejudgings (<?php echo $nrejudgings ?> active)
      </a>
    <?php } else { ?>
      <a class="item" href="rejudgings.php">
        <i class="repeat icon"></i> Rejudgings
      </a>
    <?php } ?>
  ?>
  <?php if ( have_printing() ) { ?>
    <a class="item" href="print.php">
      <i class="print icon"></i> Print
    </a>
  <?php } ?>
  <?php if ( checkrole('jury') ) { ?>
    <a class="item" href="scoreboard.php">
      <i class="file text icon"></i> Scoreboard
    </a>
  <?php } ?>

  <?php
    if ( checkrole('team') ) {
      echo "<a href=\"../team/\" class=\"item\"><i class=\"television\"></i> Team</a>\n";
    }
  ?>
  </div>
</div>

  <div class="container">
    <div class="content">
      <?php

        putClock();

        $notify_flag  =  isset($_COOKIE["domjudge_notify"])  && (bool)$_COOKIE["domjudge_notify"];
        $refresh_flag = !isset($_COOKIE["domjudge_refresh"]) || (bool)$_COOKIE["domjudge_refresh"];

        echo "<div id=\"toggles\">\n";
        if ( isset($refresh) ) {
          $text = $refresh_flag ? 'Disable' : 'Enable';
          echo '<input id="refresh-toggle" type="button" value="' . $text . ' refresh" />';
        }

        // Default hide this from view, only show when javascript and
        // notifications are available:
        echo '<div id="notify" style="display: none">' .
          addForm('toggle_notify.php', 'get') .
          addHidden('enable', ($notify_flag ? 0 : 1)) .
          addSubmit(($notify_flag ? 'Dis' : 'En' ) . 'able notifications', 'toggle_notify',
                    'return toggleNotifications(' . ($notify_flag ? 'false' : 'true') . ')') .
          addEndForm() . "</div>";

        ?>
    </div>
  </div>