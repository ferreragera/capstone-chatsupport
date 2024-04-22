<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER</title>
    <link rel="icon" href="https://cvsu.edu.ph/wp-content/uploads/2018/01/CvSU-logo-trans.png" sizes="192x192">y
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            opacity: 85%;
        }

        img {
            max-width: 100%;
            height: 80px;
            display: block;
            margin: 0 auto 20px;
        }

        label {
            display: block;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
            color: white; /* Change text color on hover */
        }

    </style>
</head>

<body>
    <div class="outer">
        <img src="images/CvSU-logo-trans.png" alt="image">
        <span class="text-white m-3">AUTOMATED RESPONSE INQUIRY SYSTEM </span>
            <div class="container mt-3">
                <h2 class="d-flex justify-content-center">Register</h2>
                <form id="formData" data-url="{{ route('createUser') }}">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" placeholder="admin">
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" placeholder="example@cvsu.edu.ph" pattern="[a-zA-Z0-9._%+-]+@cvsu\.edu\.ph$" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating ">
                        <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm Password">
                        <label for="confirmpassword">Confirm Password</label>
                    </div>
                    <button class="btn bg-success mt-4 text-white" id="registerBtn" type="submit">Register</button>
                </form>
                
            </div>
    </div>
</body>
<script>
    $(document).ready(function () {

        $('#formData').submit(function (e) {
            e.preventDefault();

            var name = $('#name').val();
            var email = $('#email').val();
            var password =  $('#password').val();
            var confirmPassword =  $('#confirmpassword').val();

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: "error",
                    title: "Passwords Mismatch",
                    text: "Passwords must match."
                });
                return;
            }

            var url = $('#formData').data('url');

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    name,
                    email,
                    password
                },
                dataType: 'JSON',
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Registered!",
                        text: "User registered successfully!"
                    }).then(function (result) {
                        if(result.isConfirmed) {
                            if(response.status == "success") {
                                window.location = "{{ route('login') }}";
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

</html>
