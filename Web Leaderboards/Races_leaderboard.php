<style>
/* Testreszabott stílusok */

body {
  background-color: #f0f8ff; /* világoskék */
  color: #333; /* sötétszürke */
  font-family: Arial, sans-serif;
  font-size: 16px;
  line-height: 1.4;
}

a {
  color: #006699; /* sötétkék */
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

h1, h2, h3 {
  color: #FFF;
  font-weight: bold;
  margin-top: 20px;
  margin-bottom: 10px;
}

button, input[type="submit"] {
  background-color: #008000; /* zöld */
  border: none;
  border-radius: 5px;
  color: #fff; /* fehér */
  cursor: pointer;
  font-size: 16px;
  margin-top: 10px;
  padding: 10px 15px;
}

button:hover, input[type="submit"]:hover {
  background-color: #006400; /* sötétzöld */
}
/* táblázatok és cellák saját keretekkel és színekkel */
table {
  border-collapse: collapse;
  border-radius: 5px;
  overflow: hidden;
}

th, td {
  border: 3px solid #AAA;
  padding: 10px;
  text-align: center;
}

td {
	  color: #FFF;
	  background: rgb(0,48,255);
	  background: radial-gradient(circle, rgba(0,48,255,1) 0%, rgba(0,212,255,1) 100%);
}

th {
  background-color: #eee;
}

/* táblázat alatti árnyék */
table {
  box-shadow: 0px 3px 25px rgba(0, 0, 0, 1.2);
}

/* oldal háttér */


body {
background: rgb(2,0,36) fixed;
background: rgb(2,0,36);
background: linear-gradient(328deg, rgba(2,0,36,1) 0%, rgba(35,232,20,1) 56%, rgba(7,28,247,1) 100%, rgba(0,212,255,1) 100%);
background-attachment: fixed;
margin-left:auto;
margin-right:auto;
}

</style>
<center>

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
					echo "<div style='width: 450px; min-height:300px; float:left;'>";
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
		echo "</div>";
    }
} else {
    echo "No events found.";
}

// adatbázis kapcsolat lezárása
mysqli_close($conn);
?>
</center>
