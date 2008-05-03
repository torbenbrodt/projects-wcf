#!/bin/bash
#
# parameters
#	$1 = TRAC_DIR
#	$2 = action (remove|add)
#	$3 = project shortname/path
#	$4 = username

echo "=== projectLeader.sh $1 $3 $4"
echo "=== `date`"
echo "==================================="

trac-admin ${1}/trac/$3 permission $2 $4 TICKET_ADMIN MILESTONE_ADMIN REPORT_ADMIN WIKI_ADMIN

