<?php

$mysqli = new mysqli("localhost","root","","user");
$result = $mysqli->query("SELECT * FROM user WHERE 1");

$rows = $result->fetch_all(MYSQLI_ASSOC);

if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}

$errors = [];
$First_Name ='';
$Last_Name ='';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $First_Name = $_POST['First_Name'];
    $Last_Name = $_POST['Last_Name'];
    

  if (!preg_match('/^[\p{L} ]+$/u', $First_Name)){
        $errors[] = 'Name must contain letters and spaces only!';
  }
  

  if(!$First_Name) {
      $errors[] = 'Must not be empty';
  }

  if(!$Last_Name) {
    $errors[] = 'Must not be empty';
  }

  

  if (empty($errors)) {
    $filename = $_FILES['Image']['name'];
    $target = "image/".basename($filename);

    if (move_uploaded_file($_FILES['Image']['tmp_name'], $target)) {
        $msg = "Image uploaded successfully";
        echo $msg;
    }else{
        $msg = "Failed to upload image";
    }
      $statment = "INSERT INTO `user`(`First Name`, `Last Name`, `Image`) VALUES ('$First_Name','$Last_Name','$filename');";
      

  mysqli_query($mysqli, $statment);
  header('Location:index.php');
  }
  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Challenge #1</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 90%;
            margin-top: 10%;
        }

        .form-group {
            margin: 10px 0px 0px 0px;
        }

        .list {
            margin-top: 100px;
        }

        
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <form method="POST" enctype="multipart/form-data" >
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo $error ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        <div class="form-group">
            <label>First Name</label>
            <input  type="text" name="First Name" class="form-control" value ="<?php echo $First_Name?>" style="width: 200px;">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input  type="text" name="Last Name" class="form-control" value ="<?php echo $Last_Name?>" style="width: 200px;">
        </div>
        <div class="form-group">
            <label >Image</label><br>
            <input type="file" name="Image" >
        </div>
        <button type="submit"  value="Upload" class="btn btn-primary" style="margin-left:70%;">Save</button>
    
    </form>
    <div class="list">

        <table class="table">
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Image</th>
        </tr>
    </thead>
    <tbody>
    <?php  foreach ($rows as $i => $row){?>
        <tr>
        <th scope="row"><?php echo $i + 1 ?></th>
        <td><?php if( $row['Image']): ?>
            <img src="image/<?php echo $filename['Image'] ?>"  style="width: 100px;height:100px">
            <?php endif ?>
        </td>
        <td><?php echo $row['First Name'] ?></td>
        <td><?php echo $row['Last Name'] ?></td>
        
        </tr>
        
        
        <?php    }
        ?>
    </tbody>

    </table>
    </div>
</div>
</body>
</html>