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
WHERE (Event.eventModeId = 24 OR Event.eventModeId = 12) 
AND event_data.EVENTID IS NOT NULL;";
$result1 = mysqli_query($conn, $sql1);

// az eredmények megjelenítése táblázatban
if (mysqli_num_rows($result1) > 0) {
    while ($row1 = mysqli_fetch_assoc($result1)) {
        $eventId = $row1['id'];
        $eventName = $row1['name'];
        $sql2 = "SELECT persona.name AS driver_name,
       MAX(event_data.servertimeended - event_data.servertimestarted) AS elapsed_time,
       MAX(CASE WHEN copsdeployed > 0 THEN bustedcount ELSE NULL END) AS bustedcount,
       MAX(CASE WHEN copsdeployed > 0 THEN copsdeployed ELSE NULL END) AS copsdeployed,
       MAX(CASE WHEN copsdeployed > 0 THEN copsdisabled ELSE NULL END) AS copsdisabled,
       MAX(CASE WHEN copsdeployed > 0 THEN costtostate ELSE NULL END) AS costtostate,
       MAX(CASE WHEN copsdeployed > 0 THEN roadblocksdodged ELSE NULL END) AS roadblocksdodged,
       MAX(CASE WHEN copsdeployed > 0 THEN distancetofinish ELSE NULL END) AS distancetofinish,
       MAX(CASE WHEN copsdeployed > 0 THEN numberofcollisions ELSE NULL END) AS numberofcollisions,
       MAX(CASE WHEN copsdeployed > 0 THEN spikestripsdodged ELSE NULL END) AS spikestripsdodged
FROM event_data
JOIN persona ON event_data.personaid = persona.id
WHERE costtostate = (SELECT MAX(costtostate) FROM event_data WHERE eventid = $eventId)
AND copsdeployed > 0
GROUP BY persona.id
ORDER BY costtostate DESC
LIMIT 30;";
        $result2 = mysqli_query($conn, $sql2);
        echo "<h2>$eventName</h2>";
        echo "<table>";
        echo "<thead><tr><th>Rank</th><th>Driver</th><th>Elapsed Time</th>
              <th>Busted Count</th><th>Cops Deployed</th><th>Cops Disabled</th>
              <th>Cost to State</th><th>Roadblocks Dodged</th><th>Distance to Finish</th>
              <th>Number of Collisions</th><th>Spike Strips Dodged</th></tr></thead>";
        echo "<tbody>";
        $i = 1;
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $driverName = $row2['driver_name'];
            $elapsedTime = $row2['elapsed_time'];
            $bustedCount = $row2['bustedcount'];
            $copsDeployed = $row2['copsdeployed'];
            $copsDisabled = $row2['copsdisabled'];
            $costToState = $row2['costtostate'];
            $roadBlocksDodged = $row2['roadblocksdodged'];
            $distanceToFinish = $row2['distancetofinish'];
            $numberOfCollisions = $row2['numberofcollisions'];
            $spikeStripsDodged = $row2['spikestripsdodged'];

            $driverName = $row2['driver_name'];
            $elapsedTime = $row2['elapsed_time'];
	
			$time_in_millis = $elapsedTime; // Átalakítandó változó
			$time_in_seconds = floor($time_in_millis / 1000); // átalakítás másodpercekre
			$display_time = gmdate("i:s", $time_in_seconds); // formázás perc:másodperc formátumba
			
            echo "<tr><td>$i</td><td>$driverName</td><td>$display_time</td>";

            // ha a copsDeployed nagyobb mint nulla, hozzáadjuk az új oszlopokat a táblázathoz
				
            if ($copsDeployed > 0) {
                echo "<td>$bustedCount</td><td>$copsDeployed</td><td>$copsDisabled</td><td>$costToState</td><td>$roadBlocksDodged</td><td>$distanceToFinish</td><td>$numberOfCollisions</td><td>$spikeStripsDodged</td>";
            }

            echo "</tr>";
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