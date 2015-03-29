<?php
    ob_start("ob_gzhandler");
    error_reporting(E_ALL);
    require_once("config.php");

    #$link = mysql_connect($host, $login, $password);
    #$mysql_select_db($database);
    $link = new mysqli($host, $login, $password, $database);
    if (isset($_GET['id'])) {
        $entry_id = $link->real_escape_string($_GET['id']);
        $ip = $link->real_escape_string($_SERVER['REMOTE_ADDR']);
        $sql = "INSERT INTO plgov_ips(entry_id, ip) VALUES ('" . $entry_id . "', '" . $ip . "')";
        $result = $link->query($sql);
        $result = $link->query("SELECT url, entry_id FROM plgov_entries e WHERE entry_id='". $entry_id ."';");
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if (!isset($_GET['m'])) {
            header("Location: " . $row['url']);
        } else {
            header("Location: https://pl.m.wikipedia.org/wiki/Specjalna:MobileDiff/" . $row['entry_id']);
        }
        die();
    }
    $docount = !isset($_GET['nocount']);
?>
<html>

<head>
<meta charset="utf-8">
<title>Edycje polskiej Wikipedii z adresów IP polskiej administracji publicznej</title>
<script type="text/javascript" src="https://d33tah.github.io/plgov/js/jquery-latest.js"></script>
<script type="text/javascript" src="https://d33tah.github.io/plgov/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
$(function() {
    $("#tablesorter").tablesorter({
//        debug: true,
        sortList: [[5,1]]
    });
});
</script>
<style>td { border: 1px black solid; } </style>
</head>

<body>
<div style="float: right"><a href="random/">Losuj edycję</a> | <a href="rules.html">Regulamin</a> | <a href="privacy.html">Polityka prywatności</a></div>
<h1>Edycje polskiej Wikipedii z adresów IP polskiej administracji publicznej</h1>
<p>Poniższa lista zawiera edycje artykułów polskiej Wikipedii z adresów IP polskiej administracji publicznej.
Jeśli chcesz dowiedzieć się o niej więcej, kliknij
<a href="https://github.com/d33tah/plgov/blob/master/README-PL.md">TUTAJ.</a></p>
<?php if ($docount) { ?>

<p><strong>Prośba:</strong> przekazując link znajomemu, skopiuj go PRZED przekierowaniem.
Aby to zrobić, kliknij link prawym przyciskiem myszy i wybierz "Kopiuj adres". W ten sposób
osoba, która otrzyma ten link podbije licznik obejrzeń dla tej edycji i pomożesz odróżnić
nieistotne edycje od tych ciekawszych. Także przekazując adres tej strony,
lepiej użyć <a href="https://git.io/plgov-random">https://git.io/plgov</a> - dzięki temu
będę mógł łatwo przenieść tę stronę na inny serwer i przekierować tam wszystkich. Dzięki!</p>
<?php } ?>
<table id="tablesorter">
<thead>
<tr>
<th>IP [WHOIS]</th>
<th>rDNS</th>
<th>Tytuł</th>
<th>Data</th>
<th>Link</th>
<?php if ($docount) { ?><th>Obejrzało osób</th><?php } ?>
</tr>
</thead>
<?
    $result = $link->query("SELECT e.*, (SELECT COUNT(ip) FROM plgov_ips WHERE entry_id=e.entry_id ) count FROM plgov_entries e ORDER BY count DESC;");
    print mysql_error($link);
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
?>
<tr>
<td><?php print $row['ip']; ?> <abbr title="WHOIS">[<a href="https://whois.domaintools.com/<?php print $row['ip']; ?>">W</a>]</abbr></td>
<td><?php print $row['rdns']; ?></td>
<td><?php print $row['title']; ?></td>
<td><?php print $row['timestamp']; ?></td>
<td><a href="?m=1&id=<?php print $row['entry_id']; ?>">LINK</a> <abbr title="dwie kolumny"><a href="?id=<?php print $row['entry_id']; ?>">[2]</a></a></td></td>
<?php if ($docount) { ?><td><?php print $row['count']; ?></td><?php } ?>
<!-- <td><a href="<?php print $row['url']; ?>">LINK</a></td> -->
</tr>
<?php
    }
?>
</table>
</body>
</html>
<?
    $link->close();
