#!/bin/sh -e
SIDES="single"
ID=$(./bin/cake queue)
if [ $ID -eq 0 ]; then
	echo Queue is empty
	exit 0
fi

pdf="$SIDES.$(date +"%F_%T").pdf"

./bin/cake queue $SIDES $ID > $pdf
nr=$(./bin/cake queue printed $ID)

echo Printed $nr lammies to file $pdf
