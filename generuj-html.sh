#!/bin/sh
echo '<table border=1>'
cat plwiki-20150219-pages-meta-history/*-govonly2 | \
    grep -v '^Interesting' | \
    sed -e "s/ u'//g" -e "s/'//g" -e "s/(//g" -e 's/)//g' \
        -e 's/^/<tr><td>/g' -e 's/$/">LINK<\/a><\/td><\/tr>/g' \
        -e 's/,/<\/td><td>/g' -e 's/https/<a href="https/g'
echo '</table>'
