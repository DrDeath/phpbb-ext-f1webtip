#!/bin/bash
#
# This file is part of the F1WebTip Extension
# Execute this script in your checked out git repository
#
# usage:   create_zip_file.sh vendor extension version branch
# example: create_zip_file.sh drdeath f1webtip 1.1.2 master
# result: a zip file named drdeath_f1webtip_1.1.2.zip containing the git repository with prefix folder structure drdeath/f1webtip/
#
# @copyright (c) Dr.Death <http://www.lpi-clan.de>
# @license GNU General Public License, version 2 (GPL-2.0)
#
#

VENDOR=$1
EXTENSION=$2
VERSION=$3
BRANCH=$4

# parameter given?
if [ $# -eq 0 ]; then
    echo "No arguments provided"
    echo "usage: create_zip_file.sh vendor extension version branch"
    exit 1
fi

if [ "$1" == "$x" ]; then
    echo "first parameter vendor missing"
    echo "usage: create_zip_file.sh vendor extension version branch"
    exit 1
fi

if [ "$2" == "$x" ]; then
    echo "second parameter extension missing"
    echo "usage: create_zip_file.sh vendor extension version branch"
    exit 1
fi

if [ "$3" == "$x" ]; then
    echo "third parameter version missing"
    echo "usage: create_zip_file.sh vendor extension version branch"
    exit 1
fi

if [ "$4" == "$x" ]; then
    echo "forth parameter branch missing"
    echo "usage: create_zip_file.sh vendor extension version branch"
    exit 1
fi

# create a zip file for EPV folder structure:  vendor/extension/
git archive --format=zip -o ${VENDOR}_${EXTENSION}_${VERSION}.zip  --prefix=$1/$2/ ${BRANCH}

echo "zip file created: ${VENDOR}_${EXTENSION}_${VERSION}.zip"

