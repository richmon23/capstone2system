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
                    <input type="text" id="contact" name="contact" maxlength="50" placeholder="" required>
                    <label for="contact">Contact</label>
                </div>
                
                <div class="address">
                    <div class="province">
                        <select id="province" onchange="loadMunicipalities()">
                            <option value="">Province</option>
                        </select>
                    </div>
                    <div class="municipality">
                        <select id="municipality" onchange="toggleAddressFields()">
                            <option value="">Municipality</option>
                        </select>
                    </div>
                    
                    <div class="barangay" id="barangayDropdown" style="display: none;">
                        <select id="barangay">
                            <option value="">Barangay</option>
                        </select>
                    </div>
 
                </div>
                <br>
                    <div class="form-group" id="addressInput" style="display: none;">
                        <input type="text" id="address" name="address" maxlength="100" placeholder="Complete Address" required>
                        <label for="address">Complete Address</label>
                    </div>
                <br>

                <label>Gender:</label><br>
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
    <script>
    const provinceDropdown = document.getElementById('province');
    const municipalityDropdown = document.getElementById('municipality');
    const barangayDropdown = document.getElementById('barangayDropdown');
    const addressInput = document.getElementById('addressInput');

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
        provinceDropdown.innerHTML = '<option value="">Province</option>';

        provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.code;
            option.textContent = province.name;
            provinceDropdown.appendChild(option);
        });
    }

    async function loadMunicipalities() {
        const provinceCode = provinceDropdown.value;
        const municipalities = await fetchAPI(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`);
        municipalityDropdown.innerHTML = '<option value="">Municipality/City</option>';

        municipalities.forEach(municipality => {
            const option = document.createElement('option');
            option.value = municipality.code;
            option.textContent = municipality.name;
            municipalityDropdown.appendChild(option);
        });

        barangayDropdown.style.display = 'none';
        addressInput.style.display = 'none';
        document.getElementById('barangay').innerHTML = '<option value="">Barangay</option>';
    }

    async function loadBarangays() {
        const municipalityCode = municipalityDropdown.value;
        const barangays = await fetchAPI(`https://psgc.gitlab.io/api/municipalities/${municipalityCode}/barangays/`);
        const barangaySelect = document.getElementById('barangay');
        barangaySelect.innerHTML = '<option value="">Barangay</option>';

        if (barangays.length > 0) {
            // If barangays are available, populate and display the barangay dropdown
            barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay.code;
                option.textContent = barangay.name;
                barangaySelect.appendChild(option);
            });
            barangayDropdown.style.display = 'block';
            addressInput.style.display = 'none';
        } else {
            // If no barangays are available, assume it's a city and display the complete address input
            barangayDropdown.style.display = 'none';
            addressInput.style.display = 'block';
        }
    }

    function toggleAddressFields() {
        const municipalityCode = municipalityDropdown.value;
        if (municipalityCode) {
            // Try to load barangays; if none, it will display the address input field
            loadBarangays();
        } else {
            barangayDropdown.style.display = 'none';
            addressInput.style.display = 'none';
        }
    }

    window.onload = loadProvinces;
</script>
</body>
</html>
