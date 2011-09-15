#! /bin/bash

ip=$(lynx -dump http://checkip.dyndns.org | cut -d : -f 2)
if [ "$ip" != "" ]
then
	echo "$ip"
else
	echo "unknown"
fi