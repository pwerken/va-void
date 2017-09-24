#!/bin/sh -e
SIDES="single"
URL="https://api.the-vortex.nl/"
ID="plin"
PASS="password"

TOKEN=$(curl -sX PUT -d "{\"id\":$ID,\"password\":\"$PASS\"}" \
	"$URL/auth/login" | sed -n '/token/{s/",//;s/.* "//;p}')
if [ -z "$TOKEN" ]; then
	echo Login failed
	exit 0
fi

ID=$(curl -s -H "Authorization: Bearer $TOKEN" -X GET "$URL/lammies/queue")
if [ "$ID" -eq 0 ]; then
	echo Queue is empty
	exit 0
fi

pdf="$SIDES.$(date +"%F_%T").pdf"
curl -s -H "Authorization: Bearer $TOKEN" -d "$ID" \
	-X POST "$URL/lammies/$SIDES" -o "$pdf"

nr=$(curl -s -H "Authorization: Bearer $TOKEN" -d "$ID" \
	-X POST "$URL/lammies/printed")

echo Printed $nr lammies to file $pdf
