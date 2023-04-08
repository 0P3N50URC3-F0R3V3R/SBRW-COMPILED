<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// adatbázis kapcsolat
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "soapbox";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// karakterkészlet beállítása mb4
mysqli_set_charset($conn, "utf8mb4_unicode_ci");

// pályák lekérdezése
$sql1 = "SELECT DISTINCT Event.id, Event.name 
FROM Event 
INNER JOIN event_data ON Event.id = event_data.EVENTID 
WHERE (Event.eventModeId = 4 OR Event.eventModeId = 9 OR Event.eventModeId = 19)
AND event_data.EVENTID IS NOT NULL;";
$result1 = mysqli_query($conn, $sql1);

// az eredmények megjelenítése táblázatban
if (mysqli_num_rows($result1) > 0) {
    while ($row1 = mysqli_fetch_assoc($result1)) {
        $eventId = $row1['id'];
        $eventName = $row1['name'];
        $sql2 = "SELECT persona.name AS driver_name, 
                        MIN(event_data.servertimeended - event_data.servertimestarted) AS elapsed_time 
                 FROM event_data 
                 JOIN persona ON event_data.personaid = persona.id 
                 WHERE eventid = $eventId
                 GROUP BY persona.id
                 ORDER BY elapsed_time ASC 
                 LIMIT 30;";
        $result2 = mysqli_query($conn, $sql2);
				    echo "<h2>$eventName</h2>";
					echo "<table>";
					echo "<thead><tr><th>Rank</th><th>Driver</th><th>Elapsed Time</th></tr></thead>";
					echo "<tbody>";
	    $i = 1;
        while ($row2 = mysqli_fetch_assoc($result2)) {
			$driverName = $row2['driver_name'];
            $elapsedTime = $row2['elapsed_time'];
		
			$time_in_millis = $elapsedTime; // Átalakítandó változó
			$time_in_seconds = floor($time_in_millis / 1000); // átalakítás másodpercekre
			$display_time = gmdate("i:s", $time_in_seconds); // formázás perc:másodperc formátumba
            echo "<tr><td>$i</td><td>$driverName</td><td>$display_time</td></tr>";
			$i++;
        }
        echo "</tbody>";
        echo "</table>";
    }
} else {
    echo "No events found.";
}

// adatbázis kapcsolat lezárása
mysqli_close($conn);
?>
