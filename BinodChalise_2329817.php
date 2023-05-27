<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "binod40";
$servername = "sql306.epizy.com";
$username = "epiz_34168793";
$password = "EikFgNg7L";
$dbname = "epiz_34168793_weather";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$table="CREATE TABLE IF NOT EXISTS `weather40` (
    `date` DATE,
    `location` VARCHAR(255),
    `temperature(°C)` DECIMAL(10, 2),
    `weather` VARCHAR(255),
    `wind(km/h)` DECIMAL(10, 2),
    `humidity(%)` INT,
    `feelslike` DECIMAL(10, 2)
  );
  ";
$conn->query($table);
if (isset($_POST['search'])) {
    $name = $_POST['search'];
    put($conn, $name);
    get($conn, $name);
} else {
    $name = "Solihull";
    put($conn, $name);
    get($conn, $name);
}

function put($conn, $name){
    $api_url = "https://api.openweathermap.org/data/2.5/weather?q=".$name."&units=metric&appid=fee0c52216fcf74e6a02307994b0d6aa";
    $json_data = file_get_contents($api_url);
    $response = json_decode($json_data, true);

    $temperature = $response["main"]["temp"];
    $weather = $response["weather"][0]["description"];
    $humidity = $response["main"]["humidity"];
    $feelslike = $response["main"]["feels_like"];
    $wind = $response["wind"]["speed"];
    $latest_date = date('Y-m-d');

    $code1 = "SELECT * FROM `weather40` WHERE date='$latest_date'";
    $code2 = "INSERT INTO `weather40`(`date`,`location`, `temperature(°C)`, `weather`, `wind(km/h)`, `humidity(%)`, `feelslike`) VALUES ('$latest_date','$name',$temperature, '$weather', $wind, $humidity, $feelslike)";
    $code3 = "UPDATE `weather40` SET `temperature(°C)`=$temperature, `location`='$name',`feelslike`=$feelslike, `weather`='$weather', `wind(km/h)`=$wind, `humidity(%)`=$humidity WHERE date='$latest_date'";
    
    $run = $conn->query($code1);
    $row = mysqli_num_rows($run);
    
    if ($row == 0) {
        $conn->query($code2);
    } elseif ($row == 1) {
        $conn->query($code3);
    }
}

function get($conn, $name){
    $code11 = "SELECT * FROM `weather40` WHERE location='$name' ORDER BY date DESC;";
    $result = $conn->query($code11);
    
    if ($result) {
        $len = mysqli_num_rows($result);
        if ($len >= 7) {
            echo "<h1>Last 7 days weather detail of $name:</h1>";
            echo "
                    <script>console.log('data from database');</script>
                    <table>

                    <tr>
                        <th>Date</th>
                        <th>Temperature (°C)</th>
                        <th>Weather</th>
                        <th>Wind (Km/h)</th>
                        <th>Humidity (%)</th>
                        <th>Feels Like</th>
                    </tr>";

            $count = 0;
            while ($count < 7 && $row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["date"]."</td>
                        <td>".$row["temperature(°C)"]."</td>
                        <td>".$row["weather"]."</td>
                        <td>".$row["wind(km/h)"]."</td>
                        <td>".$row["humidity(%)"]."</td>
                        <td>".$row["feelslike"]."</td>
                    </tr>";

                $count++;
            }

            echo "</table>
                <style>
                    body {
                        background-color: skyblue;
                    }

                    h1 {
                        text-align: center;
                        padding-top: 10px;
                    }

                    table td, th {
                        text-align: center;
                        padding: 10px 20px;
                        border-style: solid;
                        border-width: 2px;
                    }

                    table {
                        margin-left: 8%;
                        margin-top: 4%;
                    }

                    table td {
                        font-size: 30px;
                    }

                    table th {
                        font-size: 30px;
                        color: black;
                        font-weight: bolder;
                    }
                </style>";
        } else {
            echo "<p>No data found.</p>";
        }
    }
}

$conn->close();
?>
