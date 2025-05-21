<?php
// Database connection
$connection = mysqli_connect('localhost', 'root', '', 'huye', 3306);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the visitor's IP address
$ipAddress = $_SERVER['REMOTE_ADDR'];

// Get the current date
$visitDate = date('Y-m-d');

// Check if the visitor has already been recorded today
$query = "SELECT id FROM visitor_count WHERE ip_address = '$ipAddress' AND visit_date = '$visitDate'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) === 0) {
    // Insert a new record (only if the visitor is not yet recorded today)
    $insertQuery = "INSERT INTO visitor_count (ip_address, visit_date, visit_count) VALUES ('$ipAddress', '$visitDate', 1)";
    mysqli_query($connection, $insertQuery);
}

// Display a message
// echo "Your visit has been recorded for today.";

// Close the database connection
// mysqli_close($connection);




if (isset($_POST['submit'])) {
    $message = $_POST['comments'];
    $names = $_POST['names'];
    $pid = $_POST['pid'];
    $currentDateTime = date("d-m-Y H:i:s");

    // $des = $_POST['des'];

    if ($names != '' && $message != '') {
        // Check if the product name already exists in the database
        $message = mysqli_real_escape_string($connection, $message);

        $ok = mysqli_query($connection, "INSERT INTO `comments` (`cid`, `pid`, `comments`, `names`, `dates`) 
        VALUES (NULL, '$pid', '$message', '$names', '$currentDateTime')");
        if ($ok) {
            // Get the ID of the inserted product


            echo "<script>alert('Done successfully.')</script>";


        } else {
            echo "<script>alert('Failed to insert product.')</script>";
        }

    } else {
        echo "<script>alert('Please fill all fields.')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>UR-HUYE</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <!-- <link src="icon1.png" rel="icon"> -->
    <link rel="icon" href="icon1.png" sizes="32x32" type="image/png">

    <link rel="icon" href="icon1-16x16.png" sizes="16x16" type="image/png">


    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>



    <link href="css/style.css" rel="stylesheet">
    <style>
        .comment-btn {
            border: 1px solid gray;
            border-radius: 7px;
            padding: 4px 8px;
            margin-top: 0.2cm;
            display: inline-block;
            cursor: pointer;
            background-color: rgb(253, 252, 252);
            font-size: 14px;
            transition: background 0.3s ease;
            margin-left: 0.2cm;
        }

        .comment-btn:hover {
            background-color: rgb(241, 243, 245);
            border: 1px solid white;
            color: gray;


        }

        .announcement-box {
            border: 1px solid brown;
            padding: 0.3cm;
            font-size: 14px;
            text-align: justify;
            background-color: white;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .announcement-box:hover {
            background-color: #ADD8E6;
            /* Light blue */
            border-color: white;
            /* Navy */
            color: black
        }

        .announcement-box h4 {
            margin: 0;
        }

        .announcement-box p {
            margin: 0.5em 0;
        }

        .posted {
            text-align: right;
            color: #00008B
        }

        .announcement-box:hover .posted {
            color: #7B1818;
            /* Dark red */
            font-weight: bold;
            /* Bold text */
        }
    </style>


    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            /* margin-top: -0.7cm; */
            /* overflow: hidden; */
        }


        .home-title {
            /* font-size: xx-large; */
        }

        .container-xxl {
            /* height: 102vh; */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin-top: -0.4cm;
        }

        .header {
            flex: 1;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .container-xxl {
                height: auto;
                /* Allow height to adjust based on content */
                overflow: auto;
                /* Allow scrolling */
                margin-top: 2px;
            }

            .home-title {
                /* font-size: 26px; */
            }
        }

        .owl-carousel-item img {
            height: 100vh;
            width: 100%;
            object-fit: cover;
            /* Cover the container without distortion */
        }

        @media (max-width: 768px) {
            .owl-carousel-item img {
                height: auto;
            }

            .bellow-nav {
                margin-top: -0.3cm;
            }
        }

        @media (max-width: 1024px) and (min-width: 768px) {

            .container-xxl {
                height: auto;
                overflow-y: auto;
                /* Ensure content can scroll vertically */
                margin-top: 2px;
            }

            .header-carousel {
                max-height: 100%;
                /* Allow the carousel to resize properly */
                overflow-y: auto;
                /* Allow scrolling if the content overflows */
            }

            .header {
                height: auto;
                overflow-y: auto;
                /* Ensure scrolling within the header if needed */
            }

            /* Ensure the owl-carousel images are responsive */
            .owl-carousel-item img {
                height: auto;
                width: 100%;
                object-fit: cover;
            }

            .header {
                flex-direction: column;
                /* Stack vertically on tablets */
                align-items: center;
                /* Center content */
            }

            .col-md-6 {
                width: 100%;
                /* Ensure full width for stacked columns */
                text-align: center;
                /* Center text if needed */
                /* padding: 0.8cm; */
            }



            .owl-carousel-item img {
                height: 100vh;
                max-height: 100%;
            }

            .header-carousel {
                margin-top: -1.3cm;
            }
        }

        /* Disable link interaction */
        .disabled-link {
            pointer-events: none;
            cursor: not-allowed;
            text-decoration: none;
            color: gray;
        }

        /* Styling for the disabled button */
        .disabled-button {
            background-color: #f5f5f5;
            color: #999;
            border: 1px solid #ccc;
            cursor: not-allowed;
            opacity: 0.7;
        }


        .moving-text {
            background-color: red;
            color: #f1f9ff;
            position: absolute;
            /* Position it absolutely */
            top: 0;
            /* Place it at the top */
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
            z-index: 100;
            /* Ensure it stays on top of other content */
        }

        .moving-text::after {
            content: '';
            display: inline-block;
            width: 100%;
        }

        .moving-text {
            animation: scroll-left 10s linear infinite;
        }

        @keyframes scroll-left {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(-100%);
            }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-info" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">


            <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-2">
                <!-- Brand and Logo -->
                <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                    <div class="icon p-1 me-1">
                        <img class="img-fluid" src="img/ur/logo.png" alt="UR Logo" style="width: 50px; height: 50px;">
                    </div>
                    <h4 class="m-0" style="color: #064781;font-size: large;">UR HUYE CAMPUS</h4>
                </a>

                <!-- Toggler Button for small screens -->
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto p-2">
                        <a href="index.html" class="nav-item nav-link active">UR-WEBSITE</a>
                        <a href="https://cbe.ur.ac.rw/" class="nav-item nav-link">CBE</a>
                        <a href="https://cass.ur.ac.rw/" class="nav-item nav-link">CASS</a>
                        <!-- <a href="#" class="nav-item nav-link">EVENTS</a> -->
                    </div>
                    <!-- Call to Action Button -->
                    <!-- <a href="#" class="btn btn-info px-3 d-none d-lg-flex">JOIN US</a> -->

                </div>

            </nav>

            <!-- <marquee style="background-color: rgb(207, 72, 72); color: #f1f9ff;" direction="Left">
              message..................................................................
              </marquee> -->


        </div>



        <!-- Navbar End -->

        <!-- Header Start -->
        <div class="container-fluid header bg-white p-1 bellow-nav">
            <br><br>
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-4 p-md-5 mt-lg-5">
                    <h2 class="animated fadeIn"> <span class="" style="color: #064781;">
                            <b> University of Rwanda Huye Campus</b>

                        </span> </h2>
                        <p class="animated fadeIn mb-4 pb-2" style="text-align: justify;">
                            Huye is a multi-college campus. It is hosting students
                            from 3 colleges: College of arts and social sciences(CASS),
                            college of Business and Economics (CBE) and College of Medicine
                            and Health sciences(CHMS). Administration of CASS and CBE are
                            based here while administration of CHMS is based in Remera Campus.
                        </p>

                    <?php
                    $connection = mysqli_connect('localhost', 'root', '', 'huye', 3306);
                    if (!$connection) {
                        die('Connection failed: ' . mysqli_connect_error());
                    }

                    $sql = "SELECT * FROM post WHERE status='active' ORDER BY pid DESC";
                    $result = $connection->query($sql);
                    $count = 0;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $count++;
                            ?>
                            <h4>NOTICE NO <?php echo $count; ?></h4>
                            <div class='announcement-box'>
                                <p>
                                    <?php echo htmlspecialchars($row['caption']); ?>
                                    <?php if (!empty($row['link'])) { ?>
                                        <br> <a
                                            href="<?php echo htmlspecialchars($row['link']); ?>"><?php echo htmlspecialchars($row['link']); ?></a>
                                    <?php } ?>
                                    <span class='posted'>&nbsp;Posted: <?php echo htmlspecialchars($row['posted_at']); ?></span>
                                    <br>
                                    <span class="comment-btn"
                                        onclick="toggleCommentForm('comment-form-<?php echo $count; ?>')">Add Comment </span>
                                </p>

                                <div id="comment-form-<?php echo $count; ?>" class="hidden comment-form" style="display: none;">
                                    <form action="index.php" method="post" style="width: 100%;  padding: 5px; ">
                                        <input type="text" name="names" placeholder="Your name"
                                            style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
                                        <input type="text" name="pid" value="<?php echo htmlspecialchars($row['pid']); ?>"
                                            hidden placeholder="Your name"
                                            style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">
                                        <textarea name="comments" placeholder="Write your comment..." rows="4"
                                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;"></textarea>
                                        <button type="submit" name="submit"
                                            style="width: 100%; background: #064781; color: white; padding: 10px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 10px;">Submit</button>
                                    </form>


                                </div>
                            </div><br>
                            <?php
                        }
                    } else {
                        echo "<p>No active posts available.</p>";
                    }
                    $connection->close();
                    ?>

                    <script>
                        function toggleCommentForm(formId) {
                            document.querySelectorAll('.comment-form').forEach(form => {
                                if (form.id === formId) {
                                    form.style.display = form.style.display === "block" ? "none" : "block";
                                } else {
                                    form.style.display = "none";
                                }
                            });
                        }

                        function submitComment() {
                            alert("Comment submitted!");
                        }
                    </script>

                    <!-- <script>
                            window.embeddedChatbotConfig = {
                            chatbotId: "NWCXFSiT7FHYk365UQ1N5",
                            domain: "www.chatbase.co"
                            }
                            </script>
                            <script
                            src="https://www.chatbase.co/embed.min.js"
                            chatbotId="NWCXFSiT7FHYk365UQ1N5"
                            domain="www.chatbase.co"
                            defer>
                            </script> -->
                    <!-- <h4 class="animate__animated animate__fadeIn mb-4 pb-1 text-justify" style="color: #064781;font-size: large;">
                            UP COMING EVENTS
                        </h4> -->

                    <!-- Modal Structure -->

                    <a href="http://timetable.ur.ac.rw/student" class="disabled-link">
                        <button type="button" class="btn btn-outline-info m-1">Timetabling<i
                                class="bi bi-arrow-up-right"></i></button></a>
                    <!-- <a target="_blank" href="https://ur.ac.rw/spip.php?page=admissions"> <button type="button"
                            class="btn btn-outline-info m-1">Students Portal<i
                                class="bi bi-arrow-up-right"></i></button></a> -->
                    <a href="https://ienabler.ur.ac.rw/pls/prodi41/w99pkg.mi_login" class='disabled-link'> <button
                            type="button" class="btn btn-outline-info m-1">Students Portal<i
                                class="bi bi-arrow-up-right"></i></button></a>

                    <a href="https://elearning.ur.ac.rw/"> <button type="button"
                            class="btn btn-outline-info m-1">E-learning<i class="bi bi-arrow-up-right"></i></button></a>
                    <a href="./studentcard" class=""> <button type="button" class="btn btn-outline-info m-1">Students
                            card<i class="bi bi-arrow-up-right"></i></button></a>
                    <a href="https://library.ur.ac.rw/"> <button type="button"
                            class="btn btn-outline-info m-1">Library<i class="bi bi-arrow-up-right"></i></button></a>
                    <a href="http://hostel.ur.ac.rw/"> <button type="button" class="btn btn-outline-info m-1">Hostel<i
                                class="bi bi-arrow-up-right"></i></button></a>
                    <a target="_blank" href="./AssetsMS/"> <button type="button"
                            class="btn btn-outline-info m-1">Assets<i class="bi bi-arrow-up-right"></i></button></a>
                    <a target="_blank" href="./clinic/"> <button type="button" class="btn btn-outline-info m-1">Clinic<i
                                class="bi bi-arrow-up-right"></i></button></a>
                </div>


                <div class="col-md-6 animated fadeIn">
                    <div class="owl-carousel header-carousel">
                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/ur/batima12.jpg" alt="">
                        </div>
                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/ur/batima13.jpg" alt="">
                        </div>
                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/ur/batima14.jpg" alt="">
                        </div>


                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/ur/batima15.jpg" alt="">
                        </div>
                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/ur/hostel16.jpg" alt="">
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-white-50 footer pt-5  wow fadeIn" data-wow-delay="0.1s"
        style="display: none;">
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">UR HUYE CAMPUS POTAL</a>, All Right Reserved.

                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FQAs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-lg btn-info btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- <script>
    // Function to open the modal
    function openModal() {
        var myModal = new bootstrap.Modal(document.getElementById('myModal'));
        myModal.show();
    }
</script> -->
</body>

</html>