    
//  TODO: loginshow password

// const pwd = document.getElementById("password");
// const show =document.getElementById("show");

// show.onchange = () =>{
//     pwd.type = show.checked ? "text" : "password";
// };

    
//  TODO: loginshow password

function togglePasswordVisibility () {
    var passwordInput = document.getElementById('password');
    var eyeIcon = document.getElementById('eye-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = '<path d="M12 5c-7.732 0-12 7-12 7s4.268 7 12 7 12-7 12-7-4.268-7-12-7zm0 12c-2.762 0-5-2.238-5-5 0-2.761 2.238-5 5-5 2.762 0 5 2.239 5 5 0 2.762-2.238 5-5 5zm-2-5a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm4.062-2.938l-4.95 4.95 1.416 1.416 4.95-4.95-1.416-1.416z"/>';
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = '<path d="M12 5c-7.732 0-12 7-12 7s4.268 7 12 7 12-7 12-7-4.268-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>';
    }
}


