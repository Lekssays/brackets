<div class="ui stackable large menu">
  <div class="item">
    <img src="../images/brackets-logo-50.png">
  </div>
  <a class="red item" href="index.php">
    <i class="home icon"></i> Home
  </a>
	<a class="item" href="problems.php">
	  <i class="file icon"></i> Problems
	</a>
		<?php
		logged_in(); // fill userdata
		if ( checkrole('team') ) {
			echo "<a target=\"_top\" href=\"../team/\" class=\"item\"><i class=\"users icon\"></i> Team</a>\n";
		}
		if ( checkrole('jury') || checkrole('balloon') ) {
			echo "<a target=\"_top\" href=\"../jury/\" class=\"item\"><i class=\"user icon\"></i> Jury</a>\n";
		}
		if ( !logged_in() ) {
			echo "<a href=\"login.php\" class=\"item\"><i class=\"sign in icon\"></i> Login</a>\n";
		}
	?>
  </div>
</div>