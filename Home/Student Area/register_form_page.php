<?php require_once("connect.php");

if (isset($_POST['valider'])){
  
    $name=htmlspecialchars($_POST['name']);
    $surname=htmlspecialchars($_POST['surname']);
    $password=htmlspecialchars(($_POST['psw']));
    $passwordr=htmlspecialchars($_POST['psw-repeat']);
    $matricule=htmlspecialchars($_POST['matricule']);
    $date=htmlspecialchars($_POST['date']);
    $gender=htmlspecialchars($_POST['sex']);
    $phone=htmlspecialchars($_POST['phone']);
    $level=htmlspecialchars($_POST['level']);
    $serie=htmlspecialchars($_POST['serie']); 
   if ($password!==$passwordr){
    $message = 'les mots de passe ne correspondent pas ! retry';
    if (strlen($password)<5){

      $message = 'votre mot de passe est trop court retry';
     }
   }else
   {
    $testmatricule = $connexion-> prepare("SELECT * FROM etudiant WHERE matricule = ?");
    $testmatricule -> execute(array($matricule));
    $cptmatricle = $testmatricule ->rowCount();
    if($cptmatricle == 0){
    $password = sha1($password);
    $insertion = $connexion->prepare("INSERT INTO etudiant(nomE,prenomE,dateE,genre,niveau,filiere,passE,matricule) VALUES (?,?,?,?,?,?,?,?)");
    $insertion->execute(array($name,$surname,$date,$gender,$level,$serie,$password,$matricule));
    $message = "votre compte etudiant a bien ete cree !!!";
   }else{
    $message = "compte deja existant !!!";
   }
  }
   
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: black;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
  background-color: white;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
</style>
</head>
<body>

<form action="#" method="post">
  <div class="container">
    <h1>Register form for Student</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <label for="name"><b>Name</b></label>
    <input type="text" placeholder="Enter your full name" name="name" id="name" required>

    <label for="surname"><b>Surname</b></label>
    <input type="text" placeholder="give surname" name="surname" id="surname" required>

    <label for="matricule"><b>Matricule</b></label>
    <input type="text" placeholder="ETU23" name="matricule" id="matricule" required>

    <label for="surname"><b>Date of birth</b></label>
    <input type="date" placeholder="04/06/2003" name="date" id="date" required><br><br>
    
    <label for="gender"><b>Gender</b></label><br><br>
    <input type="radio" id="male" name="sex" value="M">
    <label for="surname"><b>Male</b></label>
    <input type="radio" id="female" name="sex" value="F">
    <label for="surname"><b>Female</b></label><br><br><br>
    
    <label for="surname"><b>Email</b></label>
    <input type="email" placeholder="give email" name="email" id="email" required><br><br><br>

    <label for="phone">Enter a phone number:</label><br><br>
  <input type="tel" id="phone" name="phone" placeholder="673-458-678" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}" required><br><br>
  <small>Format: (+237) 673-458-678</small><br><br>
    
    <label for="level"><b>Level</b></label><br>
    <input type="radio" id="level1" name="level" value="1">
    <label for="vehicle1"> level 1</label><br>
    <input type="radio" id="level2" name="level" value="2">
    <label for="vehicle2"> level 2</label><br>
    <input type="radio" id="level3" name="level" value="3">
    <label for="vehicle3"> licence </label><br>
    <input type="radio" id="level3" name="level" value="4">
    <label for="vehicle3"> Master </label><br><br>
    
    <label for="serie"><b>Serie</b></label>
    <input type="text" placeholder="give serie" name="serie" id="serie" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Create new" name="psw" id="psw" required>

    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
    <hr>
    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

    <button type="submit" name='valider'class="registerbtn">Register</button>
    <i style="color:red">
      <?php
      
      if (isset($message)){
      echo $message. '<br/>';
    
    }
    
      ?>

</i>
  </div>

  
  <div class="container signin">
    <p>Already have an account? <a href="#">Sign in at home</a>.</p>
  </div>
</form>

</body>

</html>
