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
        $result = $link->query("SELECT url FROM plgov_entries e WHERE entry_id='". $entry_id ."';");
        $row = $result->fetch_array(MYSQLI_ASSOC);
        header("Location: " . $row['url']);
        die();
    }
?>
<html>

<head>
<meta charset="utf-8">
<title>Edycje polskiej wikipedii z rządowych adresów IP</title>
<script type="text/javascript" src="http://tablesorter.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://tablesorter.com/__jquery.tablesorter.min.js"></script>
<script type="text/javascript">
$(function() {
    $("#tablesorter").tablesorter({debug: true, sortList: [[5,1]]});
});
</script>
<style>td { border: 1px black solid; } </style>
</head>

<body>
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
<td><a href="?id=<?php print $row['entry_id']; ?>">LINK</a></td>
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
