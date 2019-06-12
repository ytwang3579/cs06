#!/bin/tcsh

a=""
b=$#
for(( i=0; i< b; i++ ))
do
	a="$a $1"
	shift 1
done

git pull
git add .
git commit -m \'$a\'
git push
