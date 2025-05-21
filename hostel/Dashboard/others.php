<?php
include('connection.php');
include("../email_functions.php");


if (isset($_POST['reject'])) {
    $reg = $_POST['regnumber'];

    $Query = "UPDATE info SET status='rejected' WHERE regnumber='$reg'";
    $resultx = $connection->query($Query);

    if ($resultx) {
        // Fetch email for the given regnumber
        $emailQuery = "SELECT email FROM info WHERE regnumber='$reg'";
        $emailResult = $connection->query($emailQuery);

        if ($emailResult->num_rows > 0) {
            $emailRow = $emailResult->fetch_assoc();
            $recipientEmail = $emailRow['email'];

            // Send rejection email
            // sendRejectionEmail($recipientEmail, $reg);
        } else {
            echo "<script>alert('No email found for the given registration number');</script>";
        }

        // echo "<script>alert('You rejected it');</script>";
    } else {
        echo "<script>alert('Update failed');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>UR-HUYE</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="./icon1.png" rel="icon">
    <link href="./icon1.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .info-card {
            font-family: 'Poppins', sans-serif;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            /* margin-bottom: 1px; */
            /* font-style: bold; */
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif' */

        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        .formatted-underline {

            font-weight: bold;
            font-size: 20px;
            text-align: center;
            display: inline-block;
            /* Ensures the border is just under the text */
            border-bottom: 4px solid black;
            /* Creates a styled underline */
            padding-bottom: 2px;
        }
    </style>

</head>

<body style="background-color:white">
    <?php
    include("./includes/header.php");
    include("./includes/menu.php");
    ?>

    <main id="main" class="main">
        <section class="section dashboard" style="background-color:white">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12" id="studentCards">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="row">
                                <?php

                                $id = isset($_GET['regnumber']) ? mysqli_real_escape_string($connection, $_GET['regnumber']) : null;

                                // Initialize the query string
                                $query = "SELECT *, 
                                    CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(picture, '_', -1), '.', 1) AS UNSIGNED) AS timestamp 
                                FROM info
                                WHERE picture != '' 
                                AND status != 'rejected' 
                                AND yearofstudy NOT IN (1, 2, 3, 4, 5);
                                ";

                              

                              

                                $result = mysqli_query($connection, $query);

                                // Check if there are any records
                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through each record
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Output HTML card for each record
                                        ?>

                                        <div class="modal fade" id="basicModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Request</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="view_cards.php" method="post">
                                                        <div class="modal-body">
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="reject"
                                                                class="btn btn-danger">Reject</button>

                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    include('./includes/card.php');

                                    ?>


                                    <?php
                                    }
                                } else {
                                    // If no records found
                                    echo '<p>No record found</p>';
                                }
                                ?>
                        </div>
                    </div>

                </div><!-- End Records Card -->
                <button class="download-btn btn btn-outline-primary" onclick="downloadPDF()">Download PDF</button>

            </div>
            </div><!-- End Left side columns -->
        </section>
    </main><!-- End #main -->

    <!-- Load jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        function loadFonts() {
            const link = document.createElement('link');
            link.href = 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap';
            link.rel = 'stylesheet';
            document.head.appendChild(link);
        }

        function downloadPDF() {
            loadFonts(); // Ensure fonts are loaded
            const { jsPDF } = window.jspdf;
            const studentCards = document.querySelectorAll('.info-card'); // Select all student cards
            const totalCards = studentCards.length;
            const cardsPerFile = 50;
            let fileCount = 1; // Keeps track of file numbering

            // Helper function to generate PDF for each set of 50 cards
            function generatePDF(cardsSubset, fileIndex) {
                const doc = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: [85.6, 54]
                });

                doc.setFont('Poppins', 'normal'); // Set the Poppins font

                Array.from(cardsSubset).forEach((card, index) => {
                    html2canvas(card, {
                        scale: 2,
                        useCORS: true, // Ensure cross-origin images are handled
                        backgroundColor: null
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/jpeg', 1.0);
                        doc.addImage(imgData, 'JPEG', 0, 0, 90, 55); // Add card image

                        // Add new page if it's not the last card in this subset
                        if (index < cardsSubset.length - 1) {
                            doc.addPage();
                        }

                        // Save the PDF after processing the last card of this subset
                        if (index === cardsSubset.length - 1) {
                            doc.save(`student-cards-part-${fileIndex}.pdf`);
                        }
                    });
                });
            }

            // Split student cards into sets of 50 and generate PDF for each set
            for (let i = 0; i < totalCards; i += cardsPerFile) {
                const cardsSubset = Array.from(studentCards).slice(i, i + cardsPerFile); // Select subset of 50 cards
                generatePDF(cardsSubset, fileCount); // Generate PDF for this subset
                fileCount++;
            }
        }
    </script>


    <!-- ======= Footer ======= -->
    <?php
    include("./includes/footer.php");
    ?>
    <!-- End Footer -->

    <script>
        // Listen for modal trigger
        document.querySelectorAll('.col-xxl-4').forEach(card => {
            card.addEventListener('click', function () {
                // Retrieve data from the card
                const campus = this.dataset.campus;
                const college = this.dataset.college;
                const names = this.dataset.names;
                const school = this.dataset.school;
                const program = this.dataset.program;
                const yearofstudy = this.dataset.yearofstudy;
                const expireddate = this.dataset.expireddate;
                const regnumber = this.dataset.regnumber;
                const picture = this.dataset.picture;

                // Update modal content
                document.querySelector('#basicModal .modal-title').textContent = `REJECT THIS CARD`;
                document.querySelector('#basicModal .modal-body').innerHTML = `
                 <p><strong>Names:</strong> ${names} <strong> &nbsp;&nbsp; <br/> Reg Number:</strong> ${regnumber}</p>
           
                <input hidden name='regnumber'  value='${regnumber}'/>
                    <img src="${picture}" alt="Student Picture" style="max-width: 100%; height: auto;">

                 
            
                  
            `;
            });
        });
    </script>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
</body>

</html>