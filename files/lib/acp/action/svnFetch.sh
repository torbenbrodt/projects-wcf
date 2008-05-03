#!/bin/bash
#
# parameters
#	$1 = url
#	$2 = last revision
#	$3 = username
#	$4 = password

svn log $1 --username $3 --password $4 --revision $2:HEAD --xml
