<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title>Update Time Slot</title>
		<link rel="stylesheet" href="./style.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
	</head>
	
	<body>
		<h1>Update Your Time Slot</h1>
		
		<div class = "form-container">
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

				$sql = "SELECT timeSlot FROM students WHERE studentID = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s", $studentID);
				$stmt->execute();
				$result = $stmt->get_result();

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$currentTimeSlot = $row['timeSlot'];
				} else {
					echo "<p>No previous registration found.</p>";
					$conn->close();
					exit;
				}

				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$newTimeSlot = $_POST["timeSlot"];

					if ($newTimeSlot != $currentTimeSlot) {
						$conn->begin_transaction();
						try {
							$updateOldSlot = $conn->prepare("UPDATE timeslots SET available = available + 1 WHERE id = ?");
							$updateOldSlot->bind_param("i", $currentTimeSlot);
							$updateOldSlot->execute();
							$updateOldSlot->close();

							$updateNewSlot = $conn->prepare("UPDATE timeslots SET available = available - 1 WHERE id = ?");
							$updateNewSlot->bind_param("i", $newTimeSlot);
							$updateNewSlot->execute();
							$updateNewSlot->close();

							$updateStudent = $conn->prepare("UPDATE students SET timeSlot = ? WHERE studentID = ?");
							$updateStudent->bind_param("ss", $newTimeSlot, $studentID);
							$updateStudent->execute();
							$updateStudent->close();

							$conn->commit();
							echo "<p class='message'>Your time slot has been updated successfully!</p>";

						} catch (Exception $e) {
							$conn->rollback();
							echo "<p class='error'>Error updating time slot: " . $e->getMessage() . "</p>";
						}
					} else {
						echo "<p class='error'>You have selected the same time slot as before.</p>";
					}
				}
			?>

			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<label for="timeSlot">Select a new time slot:</label>
				<select id="timeSlot" name="timeSlot" required>
					<option value="" disabled selected>Select a time slot</option>
					<?php
					$sql = "SELECT id, slot, available FROM timeslots WHERE available > 0";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$id = htmlspecialchars($row["id"]);
							$slot = htmlspecialchars($row["slot"]);
							$available = htmlspecialchars($row["available"]);
							echo '<option value="' . $id . '"' . ($currentTimeSlot == $id ? ' selected' : '') . '>' . $slot . ' (' . $available . ' available slots)</option>';
						}
					}

					$conn->close();
					?>
				</select>
				<div class="submit-button">
					<input type="submit" value="Update Time Slot">
				</div>
			</form>
		</div>
		<br>
		<p><a href="index.php">Go back to the form</a></p>
		<p><a href="timeslotdata.php">To view all student data and available time slots</a></p>
	</body>
</html>
