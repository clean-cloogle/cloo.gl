#!/bin/sh
perl -pe 's/([^a-zA-Z0-9_.!~*()'\''-])/sprintf("%%%02X",ord($1))/ge' |
	xargs -I{} curl -X POST -d "type=regular&token=a&url={}" http://cloo.gl
