<?php
    ob_start();
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
?>
<html>

<head>
<meta charset="utf-8">
<title>Edycje polskiej Wikipedii z rządowych adresów IP</title>
<script type="text/javascript" src="http://tablesorter.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://tablesorter.com/__jquery.tablesorter.min.js"></script>
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
<h1>Edycje polskiej Wikipedii z rządowych adresów IP</h1>
<p>Poniższa lista zawiera edycje artykułów polskiej Wikipedii z rządowych adresów IP.
Jeśli chcesz dowiedzieć się o niej więcej, kliknij
<a href="https://github.com/d33tah/plgov/blob/master/README-PL.md">TUTAJ.</a></p>
<p><strong>Prośba:</strong> przekazując link znajomemu, skopiuj go PRZED przekierowaniem.
Aby to zrobić, kliknij link prawym przyciskiem myszy i wybierz "Kopiuj adres". W ten sposób
osoba, która otrzyma ten link podbije licznik obejrzeń dla tej edycji i pomożesz odróżnić
nieistotne edycje od tych ciekawszych. Także przekazując adres tej strony,
lepiej użyć <a href="https://git.io/plgov-random">https://git.io/plgov</a> - dzięki temu
będę mógł łatwo przenieść tę stronę na inny serwer i przekierować tam wszystkich. Dzięki!</p>
<table id="tablesorter">
<thead>
<tr>
<th>IP</th>
<th>rDNS</th>
<th>Tytuł</th>
<th>Data</th>
<th>Link</th>
<th>Obejrzało osób</th>
</tr>
</thead>
<?
    $result = $link->query("SELECT e.*, (SELECT COUNT(ip) FROM plgov_ips WHERE entry_id=e.entry_id ) count FROM plgov_entries e ORDER BY count DESC;");
    print mysql_error($link);
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
?>
<tr>
<td><?php print $row['ip']; ?> (<a href="https://who.is/whois-ip/ip-address/<?php print $row['ip']; ?>">?</a>)</td>
<td><?php print $row['rdns']; ?></td>
<td><?php print $row['title']; ?></td>
<td><?php print $row['timestamp']; ?></td>
<td><a href="?id=<?php print $row['entry_id']; ?>">LINK</a> <a href="?m=1&id=<?php print $row['entry_id']; ?>">[M]</a></td></td>
<td><?php print $row['count']; ?></td>
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