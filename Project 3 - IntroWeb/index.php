<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title>Student Demo Registration</title>
		<link rel="stylesheet" href="./style.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
	</head>
	
	<body>
		<div class="intro">
			<h1>Student Demo Registration Form</h1>
			<p>Please fill in all fields and click Submit.</p>
			<br>
		</div>

		<div class="form-container">
			<?php
			session_start();

			$servername = "thisisadatabase.cp4ayiu4gnqy.us-east-2.rds.amazonaws.com";
			$username = "admin";
			$password = "admin123";
			$dbname = "project03";

			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$studentID = $firstName = $lastName = $projectTitle = $emailAddress = $phoneNumber = $timeSlot = "";
			$errors = [];
			$infoMessage = '';

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$studentID = $_POST["studentID"];
				$firstName = $_POST["firstName"];
				$lastName = $_POST["lastName"];
				$projectTitle = $_POST["projectTitle"];
				$emailAddress = $_POST["emailAddress"];
				$phoneNumber = $_POST["phoneNumber"];
				$timeSlot = $_POST["timeSlot"];

				if (empty($studentID) || !preg_match("/^\d{8}$/", $studentID)) {
					$errors['studentID'] = "Must be 8 digits long. ex: 12345678";
				}

				if (empty($firstName) || !preg_match("/^[A-Za-z]+$/", $firstName)) {
					$errors['firstName'] = "Must be letters only.";
				}

				if (empty($lastName) || !preg_match("/^[A-Za-z]+$/", $lastName)) {
					$errors['lastName'] = "Must be letters only.";
				}

				if (empty($emailAddress) || !preg_match("/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]{1,20}(\.[a-zA-Z0-9]{1,20}){0,2}$/", $emailAddress)) {
					$errors['emailAddress'] = "Email must be in the form of test123@domain.com";
				}

				if (empty($phoneNumber) || !preg_match("/^\d{3}-\d{3}-\d{4}$/", $phoneNumber)) {
					$errors['phoneNumber'] = "Phone number must be in the form 999-999-9999";
				}

				if (empty($timeSlot)) {
					$errors['timeSlot'] = "Time Slot is required.";
				}

				if (empty($errors)) {
					$checkStmt = $conn->prepare("SELECT studentID FROM students WHERE studentID = ?");
					$checkStmt->bind_param("s", $studentID);
					$checkStmt->execute();
					$checkResult = $checkStmt->get_result();

					if ($checkResult->num_rows > 0) {
						$_SESSION['studentID'] = $studentID;
						$infoMessage = "Student ID is already registered. <a href='updatetime.php'>Click here to update your time slot.</a>";
					} else {
						$stmt = $conn->prepare("INSERT INTO students (studentID, firstName, lastName, projectTitle, emailAddress, phoneNumber, timeSlot) VALUES (?, ?, ?, ?, ?, ?, ?)");
						$stmt->bind_param("sssssss", $studentID, $firstName, $lastName, $projectTitle, $emailAddress, $phoneNumber, $timeSlot);

						if ($stmt->execute()) {
							$updateStmt = $conn->prepare("UPDATE timeslots SET available = available - 1 WHERE id = ?");
							$updateStmt->bind_param("i", $timeSlot);
							$updateStmt->execute();
							$updateStmt->close();
							
							$_SESSION['studentID'] = $studentID;

							header("Location: success.php");
							exit;
						} else {
							echo "Error: " . $stmt->error;
						}

						$stmt->close();
					}

					$checkStmt->close();
				}
			}
			$conn->close();
			?>
			
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<div>
					<label><strong>Student ID:</strong></label>
					<input type="text" 
						placeholder="Enter your 8 digit student ID" 
						name="studentID" 
						value="<?php echo htmlspecialchars($studentID); ?>" 
						title="Must use 8 digits only"
						required>
					<span class="error"><?php echo isset($errors['studentID']) ? $errors['studentID'] : ''; ?></span>
				</div>

				<div>
					<label><strong>First Name:</strong></label>
					<input type="text" 
						placeholder="Enter your first name" 
						name="firstName" 
						value="<?php echo htmlspecialchars($firstName); ?>"
						title="Alphabetic Letters only"
						required>
					<span class="error"><?php echo isset($errors['firstName']) ? $errors['firstName'] : ''; ?></span>
				</div>

				<div>
					<label><strong>Last Name:</strong></label>
					<input type="text" 
						placeholder="Enter your last name" 
						name="lastName" 
						value="<?php echo htmlspecialchars($lastName); ?>" 
						title="Alphabetic Letters only"
						required>
					<span class="error"><?php echo isset($errors['lastName']) ? $errors['lastName'] : ''; ?></span>
				</div>

				<div>
					<label><strong>Project Title:</strong></label>
					<input type="text" 
						placeholder="Enter title of your project" 
						name="projectTitle" 
						value="<?php echo htmlspecialchars($projectTitle); ?>" 
						title="Put the title of your project here"
						required>
					<span class="error"><?php echo isset($errors['projectTitle']) ? $errors['projectTitle'] : ''; ?></span>
				</div>

				<div>
					<label><strong>Email Address:</strong></label>
					<input type="email" 
						placeholder="ex: email@address.com" 
						name="emailAddress" 
						value="<?php echo htmlspecialchars($emailAddress); ?>" 
						title="Must use a valid email address in the form of test123@domain.com"
						required>
					<span class="error"><?php echo isset($errors['emailAddress']) ? $errors['emailAddress'] : ''; ?></span>
				</div>

				<div>
					<label><strong>Phone Number:</strong></label>
					<input type="text" 
						placeholder="ex: 999-999-9999" 
						name="phoneNumber" 
						value="<?php echo htmlspecialchars($phoneNumber); ?>" 
						title="Must use a valid phone number in the format 123-456-7890"
						required>
					<span class="error"><?php echo isset($errors['phoneNumber']) ? $errors['phoneNumber'] : ''; ?></span>
				</div>

				<div>
					<label><strong>Time Slot:</strong></label>
					<select name="timeSlot" required>
						<option value="" disabled selected>Select a time slot</option>
						<?php
						$conn = new mysqli($servername, $username, $password, $dbname);

						$sql = "SELECT id, slot, available FROM timeslots WHERE available > 0";
						$result = $conn->query($sql);

						if (!$result) {
							die("Query failed: " . $conn->error);
						}

						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$id = htmlspecialchars($row["id"]);
								$slot = htmlspecialchars($row["slot"]);
								$available = htmlspecialchars($row["available"]);
								echo '<option value="' . $id . '">' . $slot . ' (' . $available . ' available slots)</option>';
							}
						}

						$conn->close();
						?>
					</select>
					<span class="error"><?php echo isset($errors['timeSlot']) ? $errors['timeSlot'] : ''; ?></span>
				</div>

				<div class="submit-button">
					<input type="submit" name="submit" value="Register">
				</div>
			</form>

			<?php if ($infoMessage): ?>
				<div class="info">
					<?php echo $infoMessage; ?>
				</div>
			<?php endif; ?>
		</div>
	</body>
</html>
