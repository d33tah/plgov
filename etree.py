#!/usr/bin/python

import xml.etree.cElementTree as ET
import sys
import bz2
import re
import urllib
import IPy

IP_RE_STR = ("^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25"
             "[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]"
             "\\d|25[0-5])$")
IP_RE = re.compile(IP_RE_STR)
URL_FORMAT = "https://pl.wikipedia.org/w/index.php?title=%s&diff=prev&oldid=%s"
TAG_PREFIX = "{http://www.mediawiki.org/xml/export-0.10/}"

def in_iplist(ip, iplist):
    return IPy.IP(ip) in iplist
    #common = IPy.IPSet([IPy.IP(ip)]) & iplist
    #return common != IPy.IPSet([])

def handle_tuple(iplist, ip, title, timestamp, id_):
    if not IP_RE.match(ip):
        print("Interesting IP: %s" % ip)
    if in_iplist(ip, iplist):
        title_quoted = urllib.quote(title.encode('utf8'))
        print(ip, title, timestamp,
              URL_FORMAT % (title_quoted, id_))


def parse_file(f, iplist):
    title = None
    i = 0
    for event, element in ET.iterparse(f):
        if element.tag == TAG_PREFIX + "timestamp":
            timestamp = ''.join(element.itertext())
        if element.tag == TAG_PREFIX + "id":
            id_ = ''.join(element.itertext())
        if element.tag == TAG_PREFIX + "title":
            title = ''.join(element.itertext())
        if element.tag ==  TAG_PREFIX + "ip":
            ip = ''.join(element.itertext())
            handle_tuple(iplist, ip, title, timestamp, id_)
        element.clear()

def scan_tuples(iplist):
    from ast import literal_eval
    with open(sys.argv[1]) as f:
        for line in f:
            if line.startswith("Interesting IP: "):
                continue
            line = line.rstrip()
            ip, title, timestamp, url = literal_eval(line)
            if in_iplist(ip, iplist):
                print(line)

def main():
    sys.stderr.write("Loading IP list...")
    with open(sys.argv[2]) as iplist_f:
        iplist = IPy.IPSet(map(lambda x: IPy.IP(x.rstrip()), iplist_f.readlines()))
    sys.stderr.write("done.\n")
    #scan_tuples(iplist)
    with bz2.BZ2File(sys.argv[1]) as f:
        parse_file(f, iplist)

if __name__ == "__main__":
    main()
