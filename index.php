<?php

$servername="localhost";
$username="root";
$password="";
$database="notes";
$conn=mysqli_connect($servername,$username,$password,$database);
if(!$conn){
    echo"There was an error while connecting to the database!";
}

$insert=false;
$delete=false;
$update=false;

if(isset($_GET['delete'])){
    $sno=$_GET['delete'];
    $sql="DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
    $result=mysqli_query($conn, $sql);
    $delete=true;
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST["snoEdit"])){
        $title=$_POST['titleedit'];
        $desc=$_POST['descedit'];
        $sno=$_POST['snoEdit'];
        $update=true;
        $sql="UPDATE `notes` SET `title` = '$title', `description` = '$desc' WHERE `notes`.`sno` = $sno;";
        $result=mysqli_query($conn, $sql);      
    }
    else{
        $title=$_POST['title'];
        $desc=$_POST['desc'];

        $sql=" INSERT INTO `notes` (`title`, `description`, `date`) VALUES ('$title', '$desc', current_timestamp())";
        $result=mysqli_query($conn, $sql);
        if($result){
            $insert=true;
        }
    }
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNotes- a note taking app</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">

<body>
    <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit the note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/iNotes/index.php" method="post">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Edit Title</label>
                            <input type="text" class="form-control" id="titleedit" name="titleedit"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Edit Description</label>
                            <textarea class="form-control" id="descedit" name="descedit" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/iNotes">iNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/iNotes/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact US</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <?php
        if($insert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong> Your note has been submitted succesfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
    ?>

    <?php
        if($update){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong> Your note has been updated succesfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
    ?>

    <?php
        if($delete){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong> Your note has been deleted succesfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
    ?>

    <div class="container my-3">
        <form action="/iNotes/index.php" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Title of the note</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="container my-4">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">SNo</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $sql="SELECT * FROM `notes`";
            $result=mysqli_query($conn, $sql);
            $no=1;
            /*$row=mysqli_fetch_assoc($result);
            echo var_dump($row);*/
            while($row=mysqli_fetch_assoc($result)){
                echo "<tr>
                    <th scope='row'>".$no."</th>
                    <td>".$row['title']."</td>
                    <td>".$row['description']."</td>
                    <td><button class='btn btn-sm btn-primary edit' id=".$row['sno']." data-bs-toggle='modal' data-bs-target='#exampleModal'>Edit</button>
                    <button class='btn btn-sm btn-primary delete' id=d".$row['sno'].">Delete</button>
                    </td>
                    </tr>";
                $no+=1;
            }
        ?>
            </tbody>

        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                desc = tr.getElementsByTagName("td")[1].innerText;
                titleedit.value=title;
                descedit.value=desc;
                snoEdit.value=e.target.id;
                console.log(e.target.id);
            })
        })

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                sno=e.target.id.substr(1,);
                if(confirm("Are you sure to Delete?")){
                    window.location=`/iNotes/index.php?delete=${sno}`;
                }
            })
        })
    </script>
</body>
</html>