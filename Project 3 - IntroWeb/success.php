<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title>Submission Success</title>
		<link rel="stylesheet" href="./style.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
	</head>
	
	<body>
		<h1>Form Submitted Successfully!</h1>
		<h3>Your data has been recorded as shown below. Thank you!</h3>

		<?php
			session_start();

			if (!isset($_SESSION['studentID'])) {
				echo "<p>Error: No student ID found in session.</p>";
				exit;
			}

			$studentID = $_SESSION['studentID'];

			$servername = "thisisadatabase.cp4ayiu4gnqy.us-east-2.rds.amazonaws.com";
			$username = "admin";
			$password = "admin123";
			$dbname = "project03";

			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT s.studentID, s.firstName, s.lastName, s.projectTitle, s.emailAddress, s.phoneNumber, t.slot as timeSlot 
					FROM students s 
					JOIN timeslots t ON s.timeSlot = t.id 
					WHERE s.studentID = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("s", $studentID);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				echo '<table>
						<tr><th>Field</th><th>Value</th></tr>
						<tr><td>Student ID</td><td>' . htmlspecialchars($row['studentID']) . '</td></tr>
						<tr><td>First Name</td><td>' . htmlspecialchars($row['firstName']) . '</td></tr>
						<tr><td>Last Name</td><td>' . htmlspecialchars($row['lastName']) . '</td></tr>
						<tr><td>Project Title</td><td>' . htmlspecialchars($row['projectTitle']) . '</td></tr>
						<tr><td>Email Address</td><td>' . htmlspecialchars($row['emailAddress']) . '</td></tr>
						<tr><td>Phone Number</td><td>' . htmlspecialchars($row['phoneNumber']) . '</td></tr>
						<tr><td>Time Slot</td><td>' . htmlspecialchars($row['timeSlot']) . '</td></tr>
					</table>';
			} else {
				echo "<p>No data found for the submitted student ID.</p>";
			}
			
			unset($_SESSION['studentID']);

			$stmt->close();
			$conn->close();
		?>
		<br>
		<p><a href="index.php">Go back to the form</a></p>
		<p><a href="timeslotdata.php">To view all student data and available time slots</a></p>
	</body>
</html>
