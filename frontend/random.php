<?php
    ob_start("ob_gzhandler");
    error_reporting(E_ALL);
    require_once("config.php");
    $link = new mysqli($host, $login, $password, $database);
    $result = $link->query("SELECT * FROM plgov_entries ORDER BY RAND() LIMIT 1");
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $entry_id = $link->real_escape_string($row['entry_id']);
    $ip = $link->real_escape_string($_SERVER['REMOTE_ADDR']);
    $sql = "INSERT INTO plgov_ips(entry_id, ip) VALUES ('" . $entry_id . "', '" . $ip . "')";
    $link->query($sql);
?>
<html>

<head>
<meta charset="utf-8">
<title>Edycje polskiej Wikipedii z rządowych adresów IP</title>
</head>
<body>
<?
?>

<p>Oglądasz losową zmianę dokonaną przez adres IP należący do polskiego rządu. Więcej informacji
znajdziesz <a href="https://github.com/d33tah/plgov/blob/master/README-PL.md">TUTAJ</a>.
Aby zobaczyć następną zmianę, odśwież tę stronę (klawisz F5).</p>

<p>Twój adres IP jest nagrywany w celach statystycznych. Jeśli nie wyrażasz na to zgody, opuść tę stronę.</p>

<p><strong>Prośba:</strong> przekazując link znajomemu, skopiuj go PRZED przekierowaniem. Aby to
zrobić, kliknij link prawym przyciskiem myszy i wybierz "Kopiuj adres". W ten
sposób osoba, która otrzyma ten link podbije licznik obejrzeń dla tej edycji
i pomożesz odróżnić nieistotne edycje od tych ciekawszych. Także przekazując adres tej strony,
lepiej użyć <a href="https://git.io/plgov-random">https://git.io/plgov-random</a> - dzięki temu
będę mógł łatwo przenieść tę stronę na inny serwer i przekierować tam wszystkich. Dzięki!</p>

<p>Link: <a href="index.php?id=<?php print $row['entry_id']; ?>"><?php print $row['url']; ?></a>.
Domena: <?php print $row['rdns']; ?>.
<a href="index.php?m=1&id=<?php print $row['entry_id']; ?>">Prostsza wersja opisu zmian</a></p>

<!--<iframe src="http://pl.m.wikipedia.org/wiki/Specjalna:MobileDiff/<?php print $row['entry_id']; ?>" width="100%" height="80%" />-->

<iframe src="<?php print $row['url']; ?>" width="100%" height="70%" />

</body>
</html>
<?
    $link->close();
