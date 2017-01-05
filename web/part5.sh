#!/bin/bash -ex

DIR=$(pwd)
lsb_release -a

# start judgedaemon
cd /opt/brackets/judgehost/
bin/judgedaemon -n 0 &

# submit test programs
cd /${DIR}/tests
make check-syntax check test-stress

# wait for and check results
NUMSUBS=$(curl http://admin:admin@localhost/brackets/api/submissions | python -mjson.tool | grep -c id)
export COOKIEJAR
COOKIEJAR=$(mktemp --tmpdir)
export CURLOPTS="-sq -m 30 -b $COOKIEJAR"
curl $CURLOPTS -c $COOKIEJAR -F "cmd=login" -F "login=admin" -F "passwd=admin" "http://localhost/brackets/jury/"

while /bin/true; do
	curl $CURLOPTS "http://localhost/brackets/jury/check_judgings.php?verify_multiple=1" -o /dev/null
	NUMNOTVERIFIED=$(curl $CURLOPTS "http://localhost/brackets/jury/check_judgings.php" | grep "submissions checked" | cut -d\  -f1)
	NUMVERIFIED=$(curl $CURLOPTS "http://localhost/brackets/jury/check_judgings.php" | grep "not checked" | cut -d\  -f1)
	if [ $NUMSUBS -gt $((NUMVERIFIED+NUMNOTVERIFIED)) ]; then
		sleep 30s
	else
		break
	fi
done

# include debug output here
if [ $NUMNOTVERIFIED -ne 1 ]; then
	echo "only 1 submission is expected to be unverified, but $NUMNOTVERIFIED are"
	curl $CURLOPTS "http://localhost/brackets/jury/check_judgings.php?verify_multiple=1"
	for i in /opt/brackets/judgehost/judgings/*/*/*/compile.out; do
		echo $i;
		head -n 100 $i;
		dir=$(dirname $i)
		if [ -r $dir/testcase001/system.out ]; then
			head $dir/testcase001/system.out
			head $dir/testcase001/runguard.err
			head $dir/testcase001/program.err
			head $dir/testcase001/program.meta
		fi
		echo;
	done
	cat /proc/cmdline
	cat /chroot/brackets/etc/apt/sources.list
	exit -1;
fi
