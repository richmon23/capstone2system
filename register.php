<?php
session_start();
require_once './connection/connection.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST['firstname'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';
    $completeaddress = $_POST['completeaddress'] ?? '';
    $province = $_POST['province_name'] ?? '';
    $municipality = $_POST['municipality_name'] ?? '';
    $barangay = $_POST['barangay_name'] ?? '';

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'] ?? '';
    $profile_pic = !empty($_FILES['profile_pic']['name']) ? $_FILES['profile_pic']['name'] : null;

    // Validate the contact number (Philippine mobile number)
    if (!preg_match("/^09\d{9}$/", $contact)) {
        echo "<br><br><script>alert('Invalid contact number format. Please enter a valid Philippine mobile number (11 digits, starts with 09).');</script>";
        exit(); // Stop further processing
    }

    // Handle file upload
    $profilePic = $_FILES['profile_pic'];
    $uploadDir = "uploads/profile_pics/";
    $targetFile = $uploadDir . basename($profilePic['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($profilePic['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($profilePic['size'] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // If everything is ok, try to upload the file
    if ($uploadOk === 1) {
        if (move_uploaded_file($profilePic['tmp_name'], $targetFile)) {
            // Set profile_pic to the uploaded filename for database insertion
            $profile_pic = basename($profilePic['name']);
        } else {
            echo "Sorry, there was an error uploading your file.";
            $uploadOk = 0; // Set to 0 if file upload fails
        }
    }

    // Check if the email is already registered
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        echo "<br><br><script>alert('This email is already registered.');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (firstname, surname, email, contact, address, password, province, municipality, completeaddress, gender, profile_pic) 
        VALUES (:firstname, :surname, :email, :contact, :address, :password, :province, :municipality, :completeaddress, :gender, :profile_pic)");
        
        // Bind parameters using named placeholders
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':province', $province);
        $stmt->bindParam(':municipality', $municipality);
        $stmt->bindParam(':completeaddress', $completeaddress); 
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':profile_pic', $profile_pic);

        // Debugging: Output bound variable values
        // var_dump($firstname, $surname, $email, $contact, $address, $password, $province, $municipality, $completeaddress, $gender, $profile_pic);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<br><br><script>
            alert('Registration successful!');
            window.location.href = 'userlogin.php';
            </script>";
        } else {
            echo "<br><br><script>alert('There was an error during registration. Please try again.');</script>";
        }
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="CSS_FILE/signupform.css">
</head>
<body>
    <div class="row">
        <div class="col-6 left">
            <center>
                <h1 class="header"> TIMELESSCARE </h1>
                <br>
                <h2 class="header2">BOGO MEMORIAL PARK</h2> 
            </center>
        </div>
        <div class="col-6 right">
        <form id="signupForm" method="POST" action="register.php" enctype="multipart/form-data">
                <h2>Sign Up</h2>
                <br>
                <div class="form-group">
                    <input type="text" id="firstname" name="firstname" maxlength="50" placeholder="" required>
                    <label for="firstname">Firstname</label>
                </div>
                <div class="form-group">
                    <input type="text" id="surname" name="surname" maxlength="50" placeholder="" required>
                    <label for="surname">Surname</label>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" maxlength="50" placeholder="" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-group">
                    <!-- <input type="text" id="contact" name="contact" maxlength="11" placeholder="" required>
                    <label for="contact">Contact</label> -->
                    <input type="tel" id="contact" name="contact" maxlength="11" placeholder="" required pattern="^(09)\d{9}$" title="Please enter a valid Philippine mobile number (11 digits starting with 09)">
                    <label for="contact">Enter your mobile number</label>
                </div>
                <div class="address">
                    <div class="province">
                        <select id="province" name="province" onchange="loadMunicipalities()">
                            <option value="">Province</option>
                        </select>
                    </div>
                    <div class="municipality">
                        <select id="municipality" name="municipality" onchange="toggleAddressFields()">
                            <option value="">Municipality</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="barangay" id="barangayDropdown" name="completeaddress" style="display: none;">
                    <select id="barangay" name="barangay_code" onchange="setAddressFields()">
                    <!-- Barangay options will be populated here -->
                    </select>
                </div>
                <br>
                <div class="form-group" id="addressInput" style="display: none;">
                    <input type="text" id="address" name="completeaddress" maxlength="100" placeholder="">
                    <label for="address">Complete Address</label>
                </div>


                 <!-- Hidden inputs for province and municipality names -->
                <input type="hidden" id="province_name" name="province_name">
                <input type="hidden" id="municipality_name" name="municipality_name">
                <input type="hidden" id="barangay_name" name="barangay_name">
              
                <!-- <label>Gender:</label><br> -->
                <input type="radio" id="male" name="gender" value="Male" required>
                <label for="male" class="gender-label">Male</label><br>
                <input type="radio" id="female" name="gender" value="Female">
                <label for="female" class="gender-label">Female</label><br><br>
                
                <div class="form-group">
                    <h5>Profile Picture:</h5>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>
                </div>
                <center>
                    <button type="button" id="openTermsBtn" class="button-72" disabled>Sign Up</button>
                </center>
                <span class="account">Have an account? <a href="userlogin.php" class="signuplogin">Login</a></span>
            </form>
            <br>
        </div>      
    </div>

    
        <!-- Modal Structure -->
        <div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Terms and Conditions</h2>
            <div class="modal-body">
                <h4>Acceptance of Terms </h4><br>
                By accessing and using the Memorial Park Management System, you agree to comply with these terms and conditions. These terms govern your use of the system, including but not limited to reservations, customer services, payments, and communications.
                <br><br>
                2.System Usage The Memorial Park Management System is designed to manage and provide services related to memorial park plots, reservations, customer support, and administrative tasks. You agree to use the system only for lawful purposes.
                Misuse of the system, including unauthorized access, data tampering, or fraudulent activity, will result in immediate termination of your account.
                <br><br>
                3.ReservationsReservations for plots and services must be made through the system. You are responsible for providing accurate information when making a reservation. Incomplete or incorrect information may lead to reservation delays or cancellations.
                Any changes or cancellations to reservations must be made at least [Insert Time Period] before the scheduled date. All reservations are non-refundable.
                <br><br>
                4.PaymentsThe system supports both online and cash payment methods. All payment transactions must be completed through the methods provided in the system.
                Installments: If applicable, customers may choose to pay in installments. You are required to follow the specified payment schedule. Delayed or missed payments may result in penalties or loss of reservation (see Section 6 below).
                Online Payment Receipts: For online payments, customers must submit valid receipts via the system. Receipt validation is required before finalizing any transaction. 
                <br><br>
                5.Account Information and Privacy
                Users are responsible for maintaining the accuracy of their personal and account information within the system. Any changes should be updated promptly.
                The system will store personal data such as customer names, contact details, and transaction history. By using the system, you consent to the collection and use of this data for purposes related to the services provided by the Memorial Park.
                <br><br>
                6.Penalties for Violations
                Fake Receipts: Submitting fraudulent receipts for online payments is strictly prohibited. Violations will result in the cancellation of reservations, account suspension, and potential legal action.
                Delayed Payments: Late payments will incur a [Insert Late Fee Percentage]. If a payment is not received within [Insert Grace Period], the system may suspend your account or cancel your reservation.
                Non-payment: Failure to complete payments within [Insert Timeframe] may result in forfeiture of your reservation, termination of services, and referral to collection agencies.
                <br><br>
                7.Customer Support
                The system offers a customer chat support feature for inquiries and assistance. Support inquiries are handled during business hours. Please allow up to [Insert Response Time] for a response to your query.
                Administrators will assist with reservations, payments, and other system-related concerns.
                <br><br>
                8.Plot Management
                Memorial park plots and blocks are managed through the system. The system allows users to view available plots, reserve them, and track existing reservations.
                Any changes to reserved plots must be approved by the system administrators. Unauthorized alterations to plot reservations or customer information will result in account suspension.
                <br><br>
                9.Non-Refundable Policy
                All payments made through the Memorial Park Management System are non-refundable, including reservation fees, service charges, and installment payments. Once a payment is made, it cannot be reversed or refunded, regardless of circumstances.
                <br><br>
                10. System Access and Availability
                The Memorial Park Management System is designed to be available 24/7, barring any system maintenance or outages. We do not guarantee uninterrupted service and are not responsible for any downtime caused by technical issues.
                <br><br>
                11.Account Suspension and Termination
                We reserve the right to suspend or terminate your account at any time for violations of these terms, including but not limited to fraud, non-payment, and misuse of system functionalities.
                Terminated accounts may lose access to all data and services, including reservation history and payments.
                <br><br>
                12.Dispute Resolution
                Any disputes related to system usage, payments, or reservations must be reported within [Insert Time Period]. We will investigate and attempt to resolve all disputes within [Insert Time Period] of receiving the complaint.
                <br><br>
                13.Changes to Terms and Conditions
                These terms and conditions may be updated periodically. Users will be notified of any changes through the system dashboard. Continued use of the system after such changes constitutes acceptance of the updated terms.</p>
                <br>
                <!-- <a href="register.php" id="signupBtn" class="button-72" role="button" title="Signup">I Agree </a>-->
                <button id="agreeBtn" class="button-73">I Agree</button>
                
            </div>
        </div>
    </div>
    <script>
    // Get elements
    const modal = document.getElementById("termsModal");
    const openTermsBtn = document.getElementById("openTermsBtn");
    const agreeBtn = document.getElementById("agreeBtn");
    const closeBtn = document.getElementsByClassName("close")[0];
    const signupForm = document.getElementById("signupForm");

    // Function to check form validity
    function checkFormValidity() {
        openTermsBtn.disabled = !signupForm.checkValidity();
    }

    // Attach input event listeners to form fields
    const inputs = signupForm.querySelectorAll("input");
    inputs.forEach(input => {
        input.addEventListener("input", checkFormValidity);
    });

    // Open modal on "Sign Up" button click
    openTermsBtn.onclick = function() {
        modal.style.display = "block";
    }

    // Close modal on close button click
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    // Handle "I Agree" button click to submit the form
    agreeBtn.onclick = function() {
        modal.style.display = "none";
        signupForm.submit(); // Submit the form when "I Agree" is clicked
    }



    // TODO: ADDRESS API FUNCTIONALITY

    const provinceDropdown = document.getElementById('province');
    const municipalityDropdown = document.getElementById('municipality');
    const barangayDropdown = document.getElementById('barangay');
    const addressInput = document.getElementById('addressInput');

// Hidden inputs for province, municipality, and barangay names
const provinceNameInput = document.getElementById('province_name');
const municipalityNameInput = document.getElementById('municipality_name');
const barangayNameInput = document.getElementById('barangay_name');

async function fetchAPI(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    } catch (error) {
        console.error('Fetch error:', error);
        return [];
    }
}

async function loadProvinces() {
    const provinces = await fetchAPI('https://psgc.gitlab.io/api/provinces/');
    provinceDropdown.innerHTML = '<option value="">Select Province</option>';
    provinces.forEach(province => {
        const option = document.createElement('option');
        option.value = province.code;
        option.textContent = province.name;
        provinceDropdown.appendChild(option);
    });
}

async function loadMunicipalities() {
    const provinceCode = provinceDropdown.value;
    provinceNameInput.value = provinceDropdown.options[provinceDropdown.selectedIndex].text; // Set province name

    if (!provinceCode) return;

    const municipalities = await fetchAPI(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`);
    municipalityDropdown.innerHTML = '<option value="">Select Municipality</option>';
    municipalities.forEach(municipality => {
        const option = document.createElement('option');
        option.value = municipality.code;
        option.textContent = municipality.name;
        municipalityDropdown.appendChild(option);
    });

    // Show municipality and reset barangay dropdown
    municipalityDropdown.style.display = 'block';
    barangayDropdown.style.display = 'none';
    addressInput.style.display = 'none';
}

async function loadBarangays() {
    const municipalityCode = municipalityDropdown.value;
    municipalityNameInput.value = municipalityDropdown.options[municipalityDropdown.selectedIndex].text; // Set municipality name

    if (!municipalityCode) return;

    const barangays = await fetchAPI(`https://psgc.gitlab.io/api/municipalities/${municipalityCode}/barangays/`);
    barangayDropdown.innerHTML = '<option value="">Select Barangay</option>';
    barangays.forEach(barangay => {
        const option = document.createElement('option');
        option.value = barangay.code;
        option.textContent = barangay.name;
        barangayDropdown.appendChild(option);
    });

    // Show barangay dropdown and address input
    barangayDropdown.style.display = 'block';
    addressInput.style.display = 'block';
}

provinceDropdown.addEventListener('change', loadMunicipalities);
municipalityDropdown.addEventListener('change', loadBarangays);
barangayDropdown.addEventListener('change', () => {
    barangayNameInput.value = barangayDropdown.options[barangayDropdown.selectedIndex].text; // Set barangay name
});


document.addEventListener("DOMContentLoaded", loadProvinces);
provinceDropdown.addEventListener("change", loadMunicipalities);
municipalityDropdown.addEventListener("change", toggleAddressFields);
barangayDropdown.addEventListener("change", setBarangayName);


    window.onload = loadProvinces;
</script>
</body>
</html>

