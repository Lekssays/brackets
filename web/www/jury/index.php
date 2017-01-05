<?php
/**
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

$REQUIRED_ROLES = array('jury', 'balloon');
require('init.php');

$title = 'Jury interface';
require(LIBWWWDIR . '/header.php');
?>

<br> 
<div class="column">
	<div class="ui segment">
	<?php
		echo "<h1><center>Brackets Administrator Panel</center></h1>\n";
	?>
	<a href="balloons.php" class="ui red massive labeled icon button">
		<i class="flag icon"></i> Balloon Status
	</a>
	<?php if ( checkrole('jury') ) { ?>
		<a href="clarifications.php" class="ui red massive labeled icon button">
			<i class="inbox icon"></i> Clarifications
		</a> 
		<a href="contests.php" class="ui red massive labeled icon button">
			<i class="book icon"></i> Contests
		</a>	
		<a href="executables.php" class="ui red massive labeled icon button">
			<i class="arrow down icon"></i> Executables
		</a>
		<p></p>
		<a href="internal_errors.php" class="ui red massive labeled icon button">
			<i class="warning icon"></i> Internal Errors
		</a>
		<a href="judgehosts.php" class="ui red massive labeled icon button">
			<i class="database icon"></i> Judgehosts
		</a>	
		<a href="judgehost_restrictions.php" class="ui red massive labeled icon button">
			<i class="info icon"></i> Judgehost Restrictions
		</a>
		<p></p>
		<a href="languages.php" class="ui red massive labeled icon button">
			<i class="file code outline icon"></i> Languages
		</a>	
		<a href="problems.php" class="ui red massive labeled icon button">
			<i class="file icon"></i> Problems
		</a>
		<a href="scoreboard.php" class="ui red massive labeled icon button">
			<i class="file text icon"></i> Scoreboard
		</a>
		<a href="statistics.php" class="ui red massive labeled icon button">
			<i class="bar chart icon"></i> Statistics
		</a>
		<p></p>
		<a href="submissions.php" class="ui red massive labeled icon button">
			<i class="plus icon"></i> Submissions
		</a>	
		<a href="users.php" class="ui red massive labeled icon button">
			<i class="user icon"></i> Users
		</a>
		<a href="teams.php" class="ui red massive labeled icon button">
			<i class="users icon"></i> Teams
		</a>	
		<a href="team_categories.php" class="ui red massive labeled icon button">
			<i class="list icon"></i> Team Categories
		</a>
		<p></p>
		<a href="team_affiliations.php" class="ui red massive labeled icon button">
			<i class="flag checkered icon"></i> Team Affiliations
		</a>	
	<?php } ?>
	</div>

	<?php if ( IS_ADMIN ): ?>

	<div class="ui segment">
		<a href="config.php" class="ui black massive labeled icon button">
			<i class="configure icon"></i> Configuration Settings
		</a> 
		<a href="checkconfig.php" class="ui black massive labeled icon button">
			<i class="retweet icon"></i> Config Checker
		</a> 
		<a href="impexp.php" class="ui black massive labeled icon button">
			<i class="exchange icon"></i> Import / Export
		</a> 
		<p></p>
		<a href="genpasswds.php" class="ui black massive labeled icon button">
			<i class="lock icon"></i> Manage Team Passwords
		</a> 
		<a href="refresh_cache.php" class="ui black massive labeled icon button">
			<i class="refresh icon"></i> Refresh Scoreboard Cache
		</a> 
		<a href="check_judgings.php" class="ui black massive labeled icon button">
			<i class="wizard icon"></i> Judging Verifier
		</a> 
		<p></p>
		<a href="auditlog.php" class="ui black massive labeled icon button">
			<i class="file text outline icon"></i> Activity Log
		</a> 	
	<?php endif; ?>
	</div>
</div>



<p><br /><br /><br /><br /></p>

<?php
require(LIBWWWDIR . '/footer.php');
?>

