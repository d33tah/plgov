#!/usr/bin/python

import sys
import MySQLdb
sql = 'INSERT INTO plgov_entries (title, ip, rdns, timestamp, url) \
VALUES ("%s", "%s", "%s", "%s", "%s");'
rdns = {}
with open(sys.argv[1]) as f:
    for line in f:
        split = line.split()
        if len(split) != 2:
            continue
        rdns[split[0]] = split[1].rstrip('.')
for line in sys.stdin:
    ip, title, timestamp, url = eval(line)
    id_ = url.split('=')[-1]
    print(sql % tuple(MySQLdb.escape_string(s.encode('utf8'))
        for s in [title, ip, rdns.get(ip, ''), timestamp, url]))
