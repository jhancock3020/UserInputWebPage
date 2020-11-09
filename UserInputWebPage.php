<!DOCTYPE html>
<html>
<head> <!-- Gives the browser instructions on how to control page's dimensions and scaling -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* CSS */

.center {
  text-align: center;
}

/* Style the buttons */
.btn {
  text-align: center;
  border: 2px solid black;
  padding: 10px 16px;
  background-color: transparent;
  cursor: pointer;
  font-size: 18px;
  font-weight: bold;
}

/* Style the active class, and buttons on mouse-over */
.active,#submit:hover, .btn:hover {
  background-color: black;
  color: white;
}

body {
  background-image: url('1700.jpg');
   /* Background image is centered vertically and horizontally at all times */
  background-position: center center;
  
  /* Background image doesn't tile */
  background-repeat: no-repeat;
  
  /* Background image is fixed in the viewport so that it doesn't move when 
     the content's height is greater than the image's height */
  background-attachment: fixed;
  
  /* This is what makes the background image rescale based
     on the container's size */
  background-size: cover;
}

/* Styles upload and download buttons on form */
input[type=submit], input[type=text]{
  text-align: center;
  border: 2px solid black;
  padding: 10px 16px;
  background-color: transparent;
  cursor: pointer;
  font-size: 18px;
  margin: 5px;
  font-weight: bold;
}

/* Styles "Enter Info" instruction */
label{
  text-align: center;
  border: 2px solid white;
  padding: 10px 16px;
  background-color: black;
  cursor: pointer;
  display: inline-block;
  font-size: 18px;
  margin: 5px;
  color: white;
  font-weight: bold;
}

/* Styles user input text area */
textarea {
  resize: none;
  overflow: hidden;
  min-height: 50px;
  max-height: 200px;
  text-align: center;
  border: 2px solid white;
  padding: 10px 16px;
  background-color: black;
  cursor: pointer;
  display: inline-block;
  font-size: 10px;
  margin: 5px;
  color: white;

}
</style>
</head>
<?php
$value = "";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";
// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS myDB";
if ($conn->query($sql) === TRUE) {
  //echo "Database created successfully";
} else {
  //echo "Error creating database: " . $conn->error;
}
mysqli_select_db($conn,"myDB");
// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS SingleElementTable (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
UserInputElement VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
  //echo "Table SingleElementTable created successfully";
} else {
  //echo "Error creating table: " . $conn->error;
}

// If "upload" button is clicked
if(isset($_POST['up'])){
  // Get first (and only) UserInputElement element from table SingleElementTable
  $sql = "SELECT UserInputElement FROM SingleElementTable WHERE id = 1";
  $res = $conn->query($sql);
  $row = mysqli_fetch_array($res);
  // If UserInputElement element exists, update it 
  if (isset($row['UserInputElement'])){
    $sql = "UPDATE SingleElementTable SET UserInputElement=? WHERE id = 1";
  }
  else{ // If UserInputElement does not exist, add it
	$sql = "INSERT INTO SingleElementTable SET UserInputElement=?";
  }
// Prepare and bind user input into UserInputElement variable
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $UserInputElement);
  $UserInputElement = $_POST['a']; // a is name of textarea where user input goes. 
  $stmt->execute();
  $stmt->close();
  $conn->close();
}
// If "download" button is clicked
elseif(isset($_POST['down'])){
  // Get first (and only) UserInputElement element from table SingleElementTable
  $res = $conn->query("SELECT * FROM SingleElementTable WHERE id=1");
  $row = mysqli_fetch_assoc($res);
  // If UserInputElement element exists, set it to $value
  if (isset($row['UserInputElement'])){
      $value = $row["UserInputElement"];
  }
  $conn->close();
  $res->close();
}
?>
<body>
<div class="center">
<div id="myDIV"> <!-- Button group. Adding active to name makes button change color. If clicked, background image changes -->
  <button class="btn 1500" onclick="document.body.style.backgroundImage= 'url(1500.jpg)';">1500</button>
  <button class="btn 1600"onclick="document.body.style.backgroundImage= 'url(1600.jpg)';">1600</button>
  <button class="btn 1700 active"onclick="document.body.style.backgroundImage= 'url(1700.jpg)';">1700</button>
  <button class="btn 1800"onclick="document.body.style.backgroundImage= 'url(1800.jpg)';">1800</button>
  <button class="btn 1900"onclick="document.body.style.backgroundImage= 'url(1900.jpg)';">1900</button>
</div>
<form method="post"> <!-- HTML form for user input, includes instructions, textarea for user input, buttons to upload or download mySQL table element -->
  <label for="fname">Enter Info:</label><br>
  <textarea name="a" maxLength="350" oninput="auto_grow(this)"><?php echo $value; ?></textarea><br> <!-- When user types, javascript func changes height -->
  <input type="submit" name="up" id="submit" value="Upload"><br>
  <input type="submit" name="down" id="submit" value="Download"><br>
</form> 
</div>
<script>
// Add active class to the current button (highlight it)
var btns = document.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
  var current = document.getElementsByClassName("active");
  current[0].className = current[0].className.replace(" active", "");
  this.className += " active";
  }); 
}
// When user types, javascript func changes height of element (ie: textarea)
function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}
</script>
</body>
</html>
