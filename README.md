# Users-login
Web technologies
Assignment
Create a user system that allows:
View a list of saved users
Allows you to insert a new user (id, name, encrypted password)
Allows any user to log in using a name and password
Enables the logged-in user to log out (you can store the logged-in user's id in the Session variable)
Allow to export in php all users (id, name) either to a pdf file (as a table with user data) or to excel. (Connect the external library for export (e.g. mPDF or phpSpreadSheet) ideally using php composer and then do not submit the vendor folder as part of the solution, just attach your composer.json configuration file)
Optional: when creating a user, let the user enter the password twice and verify that the entered passwords match
Voluntary: fill in and record the date of the user's last login
Optional: if the user fails to log in based on the filled-in data, write an error message

Recommended Action
Create a database table user (id, name, password)
Create an index.php file that lists users from the users table
Create a form (fields name, password, save button) to create a new user
Create a file (e.g. new_user.php, which receives data from the form) and saves it to the database)
Create a form (name password, submit button) for user login
Create a file (e.g. login.php) that checks if there is a user with the entered name and password and, if so, logs him in (sets up the session of the logged-in user)
Create a file (e.g. logout.php),
which logs out the user (deletes the session of the logged-in user)
