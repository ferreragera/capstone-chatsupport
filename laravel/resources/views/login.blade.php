<?php
// session_start();
// include "db_conn.php";

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     function validate($data)
//     {
//         $data = trim($data);
//         $data = stripslashes($data);
//         $data = htmlspecialchars($data);
//         return $data;
//     }

//     $uname = validate($_POST['uname']);
//     $pass = validate($_POST['password']);

//     if (empty($uname)) {
//         header("Location: loginAdmin.php?error=Username is required");
//         exit();
//     } elseif (empty($pass)) {
//         header("Location: loginAdmin.php?error=Password is required");
//         exit();
//     } else {
//         // Use MySQLi with prepared statements for improved security
//         $sql = "SELECT * FROM users WHERE user_name = ?";

//         $stmt = mysqli_prepare($conn, $sql);
//         mysqli_stmt_bind_param($stmt, "s", $uname);
//         mysqli_stmt_execute($stmt);
//         $result = mysqli_stmt_get_result($stmt);
//         $user = mysqli_fetch_assoc($result);

//         if ($user && password_verify($pass, $user['password'])) {
//             // Password matches; set session variables
//             $_SESSION['user_name'] = $user['user_name'];
//             $_SESSION['name'] = $user['name'];
//             $_SESSION['id'] = $user['id'];

//             // Regenerate session ID for security
//             session_regenerate_id();
            
//             header("Location: dashboardAdmin.php");
//             exit();
//         } else {
//             header("Location: loginAdmin.php?error=Incorrect username or password");
//             exit();
//         }
//     }
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google-signin-client_id" content="322202395649-15c775d5vhh31h5vb0hust6ld9h5te11.apps.googleusercontent.com">
    <title>LOGIN</title>
    <link rel="icon" href="https://cvsu.edu.ph/wp-content/uploads/2018/01/CvSU-logo-trans.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="https://cvsu.edu.ph/wp-content/uploads/2018/01/CvSU-logo-trans.png" sizes="192x192">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient( rgba(0,0,0,.5), rgba(0,0,0,.5) ), url("images/cvsu-aerial.jpg");
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-repeat: no-repeat;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .outer {
            opacity: 80%;
        }

        img {
            max-width: 100%;
            height: 80px;
            display: block;
            margin: 0 auto 20px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 3px;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        .login-with-google-btn {
            transition: background-color .3s, box-shadow .3s;
                
            padding: 12px 16px 12px 42px;
            border: none;
            border-radius: 3px;
            box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
            
            color: #757575;
            font-size: 14px;
            font-weight: 500;
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;
            text-decoration: none;

            background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
            background-color: white;
            background-repeat: no-repeat;
            background-position: 12px 11px;
            
                &:hover {
                    box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 2px 4px rgba(0, 0, 0, .25);
                }
                
                &:active {
                    background-color: #eeeeee;
                }
                
                &:focus {
                    outline: none;
                    box-shadow: 
                    0 -1px 0 rgba(0, 0, 0, .04),
                    0 2px 4px rgba(0, 0, 0, .25),
                    0 0 0 3px #c8dafc;
                }
                
                &:disabled {
                    filter: grayscale(100%);
                    background-color: #ebebeb;
                    box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
                    cursor: not-allowed;
                }
            }
    </style>
</head>

<body>
    <div class="outer">
        <img src="images/CvSU-logo-trans.png" alt="image">
        <span class="text-white m-3">AUTOMATED RESPONSE INQUIRY SYSTEM </span>
            <div class="container mt-3">
                <h2 class="d-flex justify-content-center">Login</h2>
                <form>
                    <a href="{{ route('google-auth') }}" class="login-with-google-btn d-flex justify-content-center">
                        Sign in with Google
                    </a>
                </form>

                {{-- <div id="my-signin2"></div>
                <script>
                    function onSuccess(googleUser) {
                    console.log('Logged in as: ' + googleUser.getBasicProfile().getName());
                    }
                    function onFailure(error) { 
                    console.log(error);
                    }
                    function renderButton() {
                    gapi.signin2.render('my-signin2', {
                        'scope': 'profile email',
                        'width': 240,
                        'height': 50,
                        'longtitle': true,
                        'theme': 'dark',
                        'onsuccess': onSuccess,
                        'onfailure': onFailure
                    });
                    }
                </script> --}}

                <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
            </div>
    </div>
</body>
<script>
</script>
</html>


