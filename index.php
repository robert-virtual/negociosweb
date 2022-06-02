<html>
<head>
<style>
*{
    font-family: sans-serif;
}
body{
    background-color:#333;
    color:white;
}
form input,form button{
    display:block;
    margin:5px;
}

</style>
</head>
<body>

<?php
$servername = "localhost";
$username = "robert";
$password = "clave123";
$dbname = "negociosweb";
$conn;
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

if(isset($_POST["name"])){
    if($_POST["password"] != $_POST["confirm_password"]){
        die("Passwords do not match");
    }
    try{
        $stmt = $conn->prepare("INSERT INTO users(cedula,name,email,password) values(:cedula,:name,:email,:password)");
        unset($_POST["confirm_passsword"]);
    #foreach($_POST as $key => $value){
        #$stmt->bindParam(":$key",$value);
    #}
        $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
        $stmt->bindParam(":cedula",$_POST["cedula"]);
        $stmt->bindParam(":name",$_POST["name"]);
        $stmt->bindParam(":email",$_POST["email"]);
        $stmt->bindParam(":password",$password);
        $stmt->execute();
    }
    catch(PDOException $e) {
        echo "Error al insertar el usuario: $e";
    }

}

$users =[];
$usersquery = $conn->prepare("Select * from users");
$usersquery->execute();

$res = $usersquery->setFetchMode(PDO::FETCH_ASSOC);
foreach($usersquery->fetchAll()  as $k=>$v){
    $users[] = $v;
}

?>
<h1>Register</h1>
<form action="/"method="POST">
    <input type="text" placeholder="Cedula" name="cedula" />
    <input type="text" placeholder="Name" name="name" />
    <input type="email" placeholder="Email" name="email" />
    <input type="password" placeholder="Password" name="password" />
    <input type="password" placeholder="Confirm Password" name="confirm_password" />
    <button>Crear</button>
</form>
    <table>
    <thead>
        <tr>
            <?php
                unset($users[0]["password"]);
                foreach($users[0] as $key => $value){
            ?>
                <th><?=$key?></th>
            <?php
                }
            ?>
        </tr>
    </thead>
    <tbody>
    
    <?php
        foreach($users as $user){
            unset($user["password"]);
    ?>
    <tr>
            <?php
                foreach($user as $key => $value){
            ?>
                <td><?=$value?></td>
            <?php
                }
            ?>
        
    </tr>     
    <?php
        }
    ?>
    </tbody>
    </table>
</body>
</html>


