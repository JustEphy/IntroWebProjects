<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title>Time Slot Data </title>
		<link rel="stylesheet" href="./style.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
	</head>
	
	<body>
		<div>
			<h1>Student Registration Data</h1>
		</div>
		
		<?php 
			$servername = "thisisadatabase.cp4ayiu4gnqy.us-east-2.rds.amazonaws.com";
			$username = "admin";
			$password = "admin123";
			$dbname = "project03";
			
			$conn = new mysqli ($servername, $username, $password, $dbname);
			
			 if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
			 }
			 
			 $sql = "SELECT s.studentID, s.firstName, s.lastName, s.projectTitle, s.emailAddress, s.phoneNumber, t.slot as timeSlot
					FROM students s
					JOIN timeslots t ON s.timeSlot = t.id";
			 $result = $conn->query($sql);
			 
			 if ($result->num_rows > 0) {
				echo "<table><tr><th>Student ID</th><th>Name</th><th>Project Title</th><th>Email Address</th><th>Phone Number</th><th>Time Slot</th></tr>";
				while($row = $result->fetch_assoc()) {
					echo "<tr><td>" . $row["studentID"] . "</td><td>" . $row["firstName"] . " " . $row["lastName"] . "</td><td>" . $row["projectTitle"] . "</td><td>" . $row["emailAddress"] . "</td><td>" . $row["phoneNumber"] . "</td><td>" . $row["timeSlot"] . "</td></tr>";
					
				}
				echo "</table>";
			 } else {
				 echo "0 results";
			 }
			 
			 $conn->close();
		?>
		
		<div>
			<h1>Time Slots Available</h1>
		</div>
		
		<?php 
			$servername = "thisisadatabase.cp4ayiu4gnqy.us-east-2.rds.amazonaws.com";
			$username = "admin";
			$password = "admin123";
			$dbname = "project03";
			
			$conn = new mysqli ($servername, $username, $password, $dbname);
			
			 if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
			 }
			 
			 $sql = "SELECT * FROM timeslots";
			 $result = $conn->query($sql);
			 
			 if ($result->num_rows > 0) {
				echo "<table><tr><th> </th><th>Time Slot</th><th>Availble</th></tr>";
				while($row = $result->fetch_assoc()) {
					echo "<tr><td>" . $row["id"] . "</td><td>" . $row["slot"] . "</td><td>" . $row["available"] . "</td></tr>";
					
				}
				echo "</table>";
			 } else {
				 echo "0 results";
			 }
			 
			 $conn->close();
		?>
		<br>
		<p><a href="index.php">Go back to the form</a></p>
	</body>
</html>

