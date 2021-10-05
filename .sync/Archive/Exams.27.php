<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Table - Brand</title>

    <?php
    include("cssLinks.php");
    $page = 6;
    require("Keepmelogin.php");
    ?>
</head>
<?php
if (isset($_POST['search'])) {
    if (empty($_POST['searcher'])) {
        echo "<script>window.location.href='users.php';</script>";
        exit;
    } else {
        $searchValue = $con->real_escape_string($_POST['searcher']);
        echo "<script>window.location.href='users.php?search=$searchValue&cat=$category';</script>";
        exit;
    }
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $con->query("DELETE FROM users WHERE id=$id") or die($con->error);
    session_start();
    $_SESSION['message'] = "Record has been deleted!";
    $_SESSION['type'] = "danger";
    echo "<script>window.location.href='users.php';</script>";
    exit;
}
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $result = $con->query("SELECT * FROM users WHERE id=$id") or die($con->error);
    if (@count($result) == 1) {
        $row = $result->fetch_array();
        $efirstname = $row['fName'];
        $elastname = $row['lName'];
        $eemail = $row['email'];
        $ephone = $row['phone'];
        $erole = $row['role'];
    }
}
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $con->real_escape_string($_POST['fname']);
    $lastname = $con->real_escape_string($_POST['lname']);
    $email = $con->real_escape_string($_POST['email']);
    $phone = $con->real_escape_string($_POST['phone']);
    $pass = $con->real_escape_string($_POST['pass']);
    $salt = 'leaderfrank';
    $pwdEncrypted = sha1($pass . $salt);
    $role = $con->real_escape_string($_POST['role']);

    $con->query("UPDATE users SET fName='$firstname',lName='$lastname',email='$email',phone='$phone',password='$pwdEncrypted',role='$role' WHERE id=$id") or die($con->error);
    session_start();
    $_SESSION['message'] = "Record has been updated!";
    $_SESSION['type'] = "warning";
    echo "<script>window.location.href='users.php';</script>";
}

?>

<body id="page-top">
    <div id="wrapper">
        <?php

        include("sidebar.php");
        ?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php

                include("aboveNavbar.php");
                ?>


                <div class="container-fluid">
                    <h3 class="text-dark mb-4">Team</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 font-weight-bold">Employee Info</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 text-nowrap">
                                    <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                        <label>Show&nbsp;<select class="form-control form-control-sm custom-select custom-select-sm">
                                                <option value="10" selected="">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>&nbsp;</label>
                                    </div>
                                </div>
                                <div class="col-md-6">


                                    <input type="search" placeholder="Search" name="searcher">
                                    <select class="form-control" name="gender">


                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <select id="sel1" name="category">
                                        <option value="fName">FirstName</option>
                                        <option value="lName">LastName</option>
                                        <option value="email">Email</option>
                                        <option value="phone">Phone</option>
                                        <option value="joined_at">Date</option>
                                        <option value="role_name">Role</option>
                                    </select>
                                    <button class="btn btn-primary btn-sm" type="submit" name="search">Search</button>
                                </div>
                            </div>
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="dataTable">
                                    <thead>
                                        <!-- exam_title	exam_datetime	exam_duration	total_question	created_on	status	course_id	teacher_id	faculty_id	exam_code	 -->
                                        <tr>
                                            <th>exam title</th>
                                            <th>exam datetime</th>
                                            <th>exam duration</th>
                                            <th>total question</th>
                                            <th>created on</th>
                                            <th>status</th>
                                            <th>course title</th>
                                            <th>faculty title</th>
                                            <th>teacher name</th>
                                            <th>exam code</th>
                                            <th colspan="2">Action</th>
                                        </tr>

                                    </thead>

                                    <?php
                                    if (isset($_GET['search'])) {
                                        $searchValue = $_GET['search'];
                                        $searchResult = $con->query("SELECT * FROM role r,users us WHERE  LIKE '%$searchValue%' AND us.role=r.id") or die($con->error);
                                        while ($row = $searchResult->fetch_assoc()) {
                                            echo '
                                                <tr>
                                                    <td>' . $row['fName'] . '</td>
                                                    <td>' . $row['lName'] . '</td>
                                                    <td>' . $row['email'] . '</td>
                                                    <td>' . $row['phone'] . '</td>
                                                    <td>' . $row['joined_at'] . '</td>
                                                    <td>' . $row['role_name'] . '</td>
                                                    <td>
                                                    <a href="./users.php?edit=' . $row['exam_id'] . '"
                                                    class ="btn btn-info">Edit</a>
                                                    <a href="./process.php?delete=' . $row['exam_id'] . '"
                                                    class ="btn btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                                
                                                ';
                                        }
                                    } else {
                                        $searchResult = $conn->query("SELECT  * FROM exam  INNER JOIN teacher    ON exam.teacher_id = teacher.teacher_id  INNER JOIN faculty
                                            ON exam.faculty_id = faculty.faculty_id    AND teacher.faculty_id = faculty.faculty_id  INNER JOIN course    ON exam.course_id = course.course_id
                                                AND course.teacher_id = teacher.teacher_id    AND course.faculty_id = faculty.faculty_id  INNER JOIN user    ON teacher.user_id = user.user_id")
                                            or die($conn->error);
                                        while ($row = $searchResult->fetch_assoc()) {
                                            //exam_title	exam_datetime	exam_duration	total_question	created_on	status	course_id	teacher_id	faculty_id	exam_code	
                                            if ($row['status'] == 1) {
                                                $status = "Available";
                                            } else {
                                                $status = "Unavailable";
                                            }

                                            echo '
                                                <tr>
                                                    <td>' . $row['exam_title'] . '</td>
                                                    <td>' . $row['exam_datetime'] . '</td>
                                                    <td>' . $row['exam_duration'] . ' Minutes</td>
                                                    <td>' . $row['total_question'] . '</td>
                                                    <td>' . $row['created_on'] . '</td>
                                                    <td>' . $status . '</td>
                                                    <td>' . $row['course_title'] . '</td>
                                                    <td>' . $row['faculty_title'] . '</td>
                                                    <td>' . $row['fname'] . ' ' . $row['lname'] . '</td>
                                                    <td>' . $row['exam_code'] . '</td>
                                                    <td>
                                                    <a href="./users.php?edit=' . $row['exam_id'] . '"
                                                    class ="btn btn-info">Edit</a>
                                                    <a href="./process.php?delete=' . $row['exam_id'] . '"
                                                    class ="btn btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                                
                                                ';
                                        }
                                    }
                                    ?>

                                    <tfoot>
                                        <tr>
                                            <th>exam title</th>
                                            <th>exam datetime</th>
                                            <th>exam duration</th>
                                            <th>total question</th>
                                            <th>created on</th>
                                            <th>status</th>
                                            <th>course title</th>
                                            <th>faculty title</th>
                                            <th>teacher name</th>
                                            <th>exam code</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">
                                        Showing 1 to 10 of 27</p>
                                </div>
                                <div class="col-md-6">
                                    <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <<?php
                include('footer.php');
                ?> </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
        </div>
        <?php

        include('jsLinks.php');
        ?>
</body>

</html>