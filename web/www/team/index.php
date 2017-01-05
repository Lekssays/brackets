<?php
/**
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');
$title = specialchars($teamdata['name']);
require(LIBWWWDIR . '/header.php');

// Don't use HTTP meta refresh, but javascript: otherwise we cannot
// cancel it when the user starts editing the submit form. This also
// provides graceful degradation without javascript present.
$refreshtime = 30;

$submitted = @$_GET['submitted'];

$fdata = calcFreezeData($cdata);
$langdata = $DB->q('KEYTABLE SELECT langid AS ARRAYKEY, name, extensions
                    FROM language WHERE allow_submit = 1');

echo "<script type=\"text/javascript\">\n<!--\n";

if ( $fdata['cstarted'] || checkrole('jury') ) {
	$probdata = $DB->q('TABLE SELECT probid, shortname, name FROM problem
	                    INNER JOIN contestproblem USING (probid)
	                    WHERE cid = %i AND allow_submit = 1
	                    ORDER BY shortname', $cid);

	putgetMainExtension($langdata);

	echo "function getProbDescription(probid)\n{\n";
	echo "\tswitch(probid) {\n";
	foreach($probdata as $probinfo) {
		echo "\t\tcase '" . specialchars($probinfo['shortname']) .
		    "': return '" . specialchars($probinfo['name']) . "';\n";
	}
	echo "\t\tdefault: return '';\n\t}\n}\n\n";
}

echo "initReload(" . $refreshtime . ");\n";
echo "// -->\n</script>\n";

echo "<h4 class=\"ui red horizontal divider header\">";
echo "<i class=\"file text icon\"></i>Your Rank</h4>";
echo "<div class=\"ui container\"\n";
// Put overview of team submissions (like scoreboard)
putTeamRow($cdata, array($teamid));
echo "</div>";
echo "<br>";
echo "<div class=\"ui two column middle very relaxed stackable grid\">\n";
echo "<div class=\"column\">\n";
echo "<div class=\"ui container\"\n";
echo "<h3 class=\"ui horizontal divider header\"></h3>";

echo "<h4 class=\"ui teal horizontal divider header\">";
echo "<i class=\"plus icon\"></i>Submissions</h4>";

if ( $fdata['cstarted'] || checkrole('jury') ) {
	echo <<<HTML
	<script type = "text/javascript">
		$(function() {
			var matches = location.hash.match(/submitted=(\d+)/);
			if (matches) {
				var \$p = \$('<p/>').html('submission done <a href="index.php">x</a>');
				\$('#submitlist > .teamoverview').after(\$p);
				\$('table.submissions tr[data-submission-id=' + matches[1] + ']').addClass('highlight');

				\$('.submissiondone a').on('click', function() {
					\$(this).parent().remove();
					\$('table.submissions tr.highlight').removeClass('highlight');
					reloadLocation = 'index.php';
				});
			}
		});
	</script>
HTML;
	$maxfiles = dbconfig_get('sourcefiles_limit',100);

	echo addForm('upload.php','post',null,'multipart/form-data', null,
		     ' onreset="resetUploadForm('.$refreshtime .', '.$maxfiles.');"') .
	    "<p id=\"submitform\">\n\n";

	echo "<input type=\"file\" name=\"code[]\" id=\"maincode\" required";
	if ( $maxfiles > 1 ) {
		echo " multiple";
	}
	echo " />\n";
	echo "<h4></h4>";
	$probs = array();
	foreach($probdata as $probinfo) {
		$probs[$probinfo['probid']]=$probinfo['shortname'];
	}
	$probs[''] = 'problem';
	echo addSelect('probid', $probs, '', true);
	$langs = array();
	foreach($langdata as $langid => $langdata) {
		$langs[$langid] = $langdata['name'];
	}
	echo "<br>";
	$langs[''] = 'language';
	echo addSelect('langid', $langs, '', true);
	echo "<br>";
	echo addSubmit('Submit', 'submit',
		       "return checkUploadForm();");

	echo addReset('Cancel');

	if ( $maxfiles > 1 ) {
		echo "<br /><span id=\"auxfiles\"></span>\n" .
		    "<input type=\"button\" name=\"addfile\" id=\"addfile\" " .
		    "value=\"Add another file\" onclick=\"addFileUpload();\" " .
		    "disabled=\"disabled\" />\n";
	}
	echo "<script type=\"text/javascript\">initFileUploads($maxfiles);</script>\n\n";

	echo "</p>\n</form>\n\n";
}

// call putSubmissions function from common.php for this team.
$restrictions = array( 'teamid' => $teamid );
putSubmissions(array($cdata['cid'] => $cdata), $restrictions, null, $submitted);

echo "</div>\n</div>\n";

echo "<div class=\"column\">";
echo "<div class=\"ui container\"";
echo "<div class=\"ui selectable celled table\">\n";

$requests = $DB->q('SELECT c.*, cp.shortname, t.name AS toname, f.name AS fromname
                    FROM clarification c
                    LEFT JOIN problem p USING(probid)
                    LEFT JOIN contestproblem cp USING (probid, cid)
                    LEFT JOIN team t ON (t.teamid = c.recipient)
                    LEFT JOIN team f ON (f.teamid = c.sender)
                    WHERE c.cid = %i AND c.sender = %i
                    ORDER BY submittime DESC, clarid DESC', $cid, $teamid);

$clarifications = $DB->q('SELECT c.*, cp.shortname, t.name AS toname, f.name AS fromname,
                          u.mesgid AS unread
                          FROM clarification c
                          LEFT JOIN problem p USING (probid)
                          LEFT JOIN contestproblem cp USING (probid, cid)
                          LEFT JOIN team t ON (t.teamid = c.recipient)
                          LEFT JOIN team f ON (f.teamid = c.sender)
                          LEFT JOIN team_unread u ON (c.clarid=u.mesgid AND u.teamid = %i)
                          WHERE c.cid = %i AND c.sender IS NULL
                          AND ( c.recipient IS NULL OR c.recipient = %i )
                          ORDER BY c.submittime DESC, c.clarid DESC',
                         $teamid, $cid, $teamid);

echo "<h4 class=\"ui blue horizontal divider header\">";
echo "<i class=\"inbox icon\"></i>Clarifications</h4>";

if ( $clarifications->count() == 0 ) {
	echo "<p>No clarifications.</p>\n\n";
} else {
	putClarificationList($clarifications,$teamid);
}

echo "<h4 class=\"ui blue horizontal divider header\">";
echo "<i class=\"inbox icon\"></i>Clarification Requests</h4>";

if ( $requests->count() == 0 ) {
	echo "<p>No clarification requests.</p>\n\n";
} else {
	putClarificationList($requests,$teamid);
}

echo addForm('clarification.php','get') .
	"<p>" . addSubmit('Request Clarification') . "</p>" .
	addEndForm();


echo "</div>\n</div>\n</div>\n</div>\n";

require(LIBWWWDIR . '/footer.php');

