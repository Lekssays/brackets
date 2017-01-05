<?php
/**
 * Generated from 'relations.php.in' on Tue Jan  3 01:36:46 WET 2017.
 *
 * Document relations between DOMjudge tables for various use.
 * The data is extracted from the SQL DB structure file.
 */

/** For each table specify the set of attributes that together
 *  are considered the primary key / superkey. */
$KEYS = array(
// @KEYS@
	'auditlog' => array('logid'),
	'balloon' => array('balloonid'),
	'clarification' => array('clarid'),
	'configuration' => array('configid'),
	'contest' => array('cid'),
	'contestproblem' => array('cid','probid'),
	'contestteam' => array('teamid','cid'),
	'event' => array('eventid'),
	'executable' => array('execid'),
	'internal_error' => array('errorid'),
	'judgehost' => array('hostname'),
	'judgehost_restriction' => array('restrictionid'),
	'judging' => array('judgingid'),
	'judging_run' => array('runid'),
	'language' => array('langid'),
	'problem' => array('probid'),
	'rankcache' => array('cid','teamid'),
	'rejudging' => array('rejudgingid'),
	'role' => array('roleid'),
	'scorecache' => array('cid','teamid','probid'),
	'submission' => array('submitid'),
	'submission_file' => array('submitfileid'),
	'team' => array('teamid'),
	'team_affiliation' => array('affilid'),
	'team_category' => array('categoryid'),
	'team_unread' => array('teamid','mesgid'),
	'testcase' => array('testcaseid'),
	'user' => array('userid'),
	'userrole' => array('userid', 'roleid'),
);

/** For each table, list all attributes that reference foreign keys
 *  and specify the source of that key. Appended to the
 *  foreign key is '&<ACTION>' where ACTION can be any of the
 *  following referential actions on delete of the foreign row:
 *  CASCADE:  also delete the source row
 *  SETNULL:  set source key to NULL
 *  RESTRICT: disallow delete of foreign row
 *  NOCONSTRAINT: no constraint is specified, even though the field
 *                references a foreign key.
 */
$RELATIONS = array(
// @RELATIONS@
	'auditlog' => array(
	),

	'balloon' => array(
		'submitid' => 'submission.submitid&CASCADE',
	),

	'clarification' => array(
		'cid' => 'contest.cid&CASCADE',
		'respid' => 'clarification.clarid&SETNULL',
		'probid' => 'problem.probid&SETNULL',
	),

	'configuration' => array(
	),

	'contest' => array(
	),

	'contestproblem' => array(
		'cid' => 'contest.cid&CASCADE',
		'probid' => 'problem.probid&CASCADE',
	),

	'contestteam' => array(
		'cid' => 'contest.cid&CASCADE',
		'teamid' => 'team.teamid&CASCADE',
	),

	'event' => array(
		'cid' => 'contest.cid&CASCADE',
		'clarid' => 'clarification.clarid&CASCADE',
		'langid' => 'language.langid&CASCADE',
		'probid' => 'problem.probid&CASCADE',
		'submitid' => 'submission.submitid&CASCADE',
		'judgingid' => 'judging.judgingid&CASCADE',
		'teamid' => 'team.teamid&CASCADE',
	),

	'executable' => array(
	),

	'internal_error' => array(
		'judgingid' => 'judging.judgingid&SETNULL',
		'cid' => 'contest.cid&SETNULL',
	),

	'judgehost' => array(
		'restrictionid' => 'judgehost_restriction.restrictionid&SETNULL',
	),

	'judgehost_restriction' => array(
	),

	'judging' => array(
		'cid' => 'contest.cid&CASCADE',
		'submitid' => 'submission.submitid&CASCADE',
		'judgehost' => 'judgehost.hostname&RESTRICT',
		'rejudgingid' => 'rejudging.rejudgingid&SETNULL',
		'prevjudgingid' => 'judging.judgingid&SETNULL',
	),

	'judging_run' => array(
		'testcaseid' => 'testcase.testcaseid&RESTRICT',
		'judgingid' => 'judging.judgingid&CASCADE',
	),

	'language' => array(
	),

	'problem' => array(
	),

	'rankcache' => array(
	),

	'rejudging' => array(
		'userid_start' => 'user.userid&SETNULL',
		'userid_finish' => 'user.userid&SETNULL',
	),

	'role' => array(
	),

	'scorecache' => array(
	),

	'submission' => array(
		'cid' => 'contest.cid&CASCADE',
		'teamid' => 'team.teamid&CASCADE',
		'probid' => 'problem.probid&CASCADE',
		'langid' => 'language.langid&CASCADE',
		'judgehost' => 'judgehost.hostname&SETNULL',
		'origsubmitid' => 'submission.submitid&SETNULL',
		'rejudgingid' => 'rejudging.rejudgingid&SETNULL',
	),

	'submission_file' => array(
		'submitid' => 'submission.submitid&CASCADE',
	),

	'team' => array(
		'categoryid' => 'team_category.categoryid&CASCADE',
		'affilid' => 'team_affiliation.affilid&SETNULL',
	),

	'team_affiliation' => array(
	),

	'team_category' => array(
	),

	'team_unread' => array(
		'teamid' => 'team.teamid&CASCADE',
		'mesgid' => 'clarification.clarid&CASCADE',
	),

	'testcase' => array(
		'probid' => 'problem.probid&CASCADE',
	),

	'user' => array(
		'teamid' => 'team.teamid&SETNULL',
	),

	'userrole' => array(
		'userid' => 'user.userid&CASCADE',
		'roleid' => 'role.roleid&CASCADE',
	),

);

// Additional relations not encoded in the SQL structure:
$RELATIONS['clarification']['sender']    = 'team.teamid&NOCONSTRAINT';
$RELATIONS['clarification']['recipient'] = 'team.teamid&NOCONSTRAINT';

/**
 * Check whether some primary key is referenced in any
 * table as a foreign key.
 *
 * Returns null or an array "table name => action" where matches are found.
 */
function fk_check($keyfield, $value) {
	global $RELATIONS, $DB;

	$ret = array();
	foreach ( $RELATIONS as $table => $keys ) {
		foreach ( $keys as $key => $val ) {
			@list( $foreign, $action ) = explode('&', $val);
			if ( empty($action) ) $action = 'CASCADE';
			if ( $foreign == $keyfield ) {
				$c = $DB->q("VALUE SELECT count(*) FROM $table WHERE $key = %s", $value);
				if ( $c > 0 ) $ret[$table] = $action;
			}
		}
	}

	if ( count($ret) ) return $ret;
	return null;
}

/**
 * Find all dependent tables that a delete could cascade into.
 *
 * Returns an array of table names.
 */
function fk_dependent_tables($table)
{
	global $RELATIONS;

	$ret = array();
	// We do a BFS through the list of tables.
	$queue = array($table);
	while ( count($queue)>0 ) {
		$curr_table = reset($queue);
		unset($queue[array_search($curr_table,$queue)]);

		if ( in_array($curr_table,$ret) ) continue;
		if ( $curr_table!=$table ) $ret[] = $curr_table;

		foreach ( $RELATIONS as $next_table => $data ) {
			foreach ( $data as $val ) {
				@list( $foreign, $action ) = explode('&', $val);
				@list( $foreign_table, $foreign_key ) = explode('.', $foreign);
				if ( empty($action) ) $action = 'CASCADE';
				if ( $foreign_table==$curr_table && $action=='CASCADE' ) {
					$queue[] = $next_table;
				}
			}
		}
	}

	return $ret;
}
