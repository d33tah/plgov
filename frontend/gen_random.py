#!/usr/bin/python
# -*- coding: utf-8 -*-

from lxml import html
import json
t = html.parse("http://kolos.math.uni.lodz.pl/~d33tah/plgov/")
coll = []
for tr in t.xpath('//tr')[1:]:
    ip = tr[0].text
    rdns = tr[1].text
    id_ = tr[4][0].get('href').split('id=')[1]
    coll += [[rdns, id_]]
print("""<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<script>""")
print("edits = " + json.dumps(coll))
print("""
function nextRandom() {
el = edits[Math.floor(Math.random() * (edits.length))];
url = "https://pl.wikipedia.org/w/index.php?diff=prev&oldid=" + el[1];
url_simple = "https://pl.m.wikipedia.org/w/index.php?diff=prev&oldid=" + el[1];
document.getElementsByTagName("iframe")[0].setAttribute("src", url);
document.getElementById("domena").innerHTML = el[0];
document.getElementById("link").innerHTML = url;
document.getElementById("link").setAttribute("href", url);
document.getElementById("prostsza").setAttribute("href", url_simple);
}
</script>
<title>Edycje polskiej Wikipedii z rządowych adresów IP</title>
</head>
<body onload="nextRandom()">

<p>Oglądasz losową zmianę dokonaną przez adres IP należący do polskiego rządu. Więcej informacji
znajdziesz <a href="https://github.com/d33tah/plgov/blob/master/README-PL.md">TUTAJ</a>.
Aby zobaczyć następną zmianę, kliknij <button onclick="nextRandom();" />tutaj</button>.</p>

<p>Link: <a id="link"></a>.
Domena: <span id="domena"></span>.
<a id="prostsza" href="">Prostsza wersja opisu zmian</a>   </p>


<iframe src="" height="85%" width="100%">
</iframe></body></html>
""")
