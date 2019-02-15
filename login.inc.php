<?php
/*
 * login.inc.php
 * handles the login request from the user.
 * Created by: Callum-James Smith(cs18804)
*/

if (isset($_POST['signin-submit']))
{
    require 'db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email))
    // Checks to see if any of the fields are empty
    {
        header("Location: index.php?error=emptyemail");
        // ^ throws an error and re-applies the data back into the fields if available
        exit(); // Stop script from running
    } else if (empty($password)) {
        header("Location: index.php?error=emptypassword?email=".$email);
        exit();
    }
    else // If fields are NOT empty
    {
        $EmailQuery = "SELECT email FROM users WHERE email='".$email."'"; // The query string
        $DBEmail = mysqli_query($connection, $EmailQuery); // The actual query
        $DBEmailRows = mysqli_num_rows($DBEmail); // Obtains the number of rows from the query

        if ($DBEmailRows >= 1) // If a row exists the the username is correct
        {

            $PasswordQuery ="SELECT * FROM users WHERE email='".$email."'";
            $DBPasswordSQL = mysqli_query($connection, $PasswordQuery);
            $row = mysqli_fetch_assoc($DBPasswordSQL);
            $PasswordCheck = password_verify($password, $row['password']);

            if ($PasswordCheck == true) // If so then check to see of the password for that user matches
            {
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];

                header("Location: landing.php?signin=success");
                exit();
            } else {
                header("Location: index.php?error=invaliddetails");
                exit();
            }

        } else {
            header("Location: index.php?error=invaliddetails");
            exit();
        }
    }

}
else if (isset($_POST['signup-submit'])) // If the user selects the signup-submit button
{
    header("Location: signup.php");
    exit();
}