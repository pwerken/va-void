#!/bin/sh -e
SIDES="single"
SIDES="double"

{
	ID=$(cake queue)
	if [ $ID -eq 0 ]; then
	#	echo "$(date +"%F_%T") Queue is empty"
		exit 0
	fi

	mkdir -p pdfs
	pdf="pdfs/$SIDES.$(date +"%F_%T").pdf"
	cake queue $SIDES $ID > "$pdf"
	lpr "$pdf"

	nr=$(cake queue printed $ID)
	echo "$(date +"%F_%T") Printed $nr lammies to '$pdf'"
} 2>&1 | tee -a log
