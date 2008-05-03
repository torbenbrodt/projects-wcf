#!/bin/bash
#
# parameters
#	$1 = SVN_DIR
#	$2 = TRAC_DIR
#	$3 = project shortname/path
#	$4 = longname

echo "=== project.sh $3 $4"
echo "=== `date`"
echo "==================================="

echo "<Location /${3}> require group ${3} </Location>" > ${1}/conf/svn-${3}.conf
echo "<Location /trac/${3}> require group ${3} </Location>" > ${2}/conf/trac-${3}.conf

# make a clean directory
#rm -rf ${1}/svn/$3
#mkdir ${1}/svn/$3

# create temporary filesystem
mkdir /tmp/$3
mkdir /tmp/$3/branches
mkdir /tmp/$3/tags
mkdir /tmp/$3/trunk

# create svn system
svnadmin create ${1}/svn/$3
svn import /tmp/$3 file://${1}/svn/$3 -m "initial import"
rm -rf /tmp/$3

# create trac system
rm -rf ${2}/trac/$3
trac-admin ${2}/trac/$3 initenv $4 sqlite:db/trac.db ${1}/svn/$3 /usr/share/trac/templates

# change privileges of guests
trac-admin ${2}/trac/$3 permission remove anonymous WIKI_CREATE WIKI_MODIFY

# change ownership
chown -R www-data.www-data ${1}/svn/$3
chown -R www-data.www-data ${2}/trac/$3
