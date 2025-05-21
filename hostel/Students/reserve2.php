<?php
session_start();
include("connection.php");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit();
}

// Get student information from session
$student_id = $_SESSION['student_id'];
$student_campus = $_SESSION['student_campus'];
$student_gender = $_SESSION['student_gender'];
$student_year = $_SESSION['student_year'];
$student_regnumber = $_SESSION['student_regnumber'];

// Check if student has an active application
$application_query = "SELECT a.*, r.room_code, h.name as hostel_name, a.slep
                     FROM applications a
                     JOIN rooms r ON a.room_id = r.id
                     JOIN hostels h ON r.hostel_id = h.id
                     WHERE a.regnumber = ? AND a.status != 'rejected'";
$app_stmt = $connection->prepare($application_query);
$app_stmt->bind_param("s", $student_regnumber);
$app_stmt->execute();
$current_application = $app_stmt->get_result()->fetch_assoc();

// Get available hostels for student's campus
$hostel_query = "SELECT h.*, c.name as campus_name 
                FROM hostels h 
                JOIN campuses c ON h.campus_id = c.id 
                WHERE c.name = ?";
$stmt = $connection->prepare($hostel_query);
$stmt->bind_param("s", $student_campus);
$stmt->execute();
$hostels = $stmt->get_result();

// Function to check hostel eligibility
function checkHostelEligibility($connection, $hostel_id, $student_gender, $student_year) {
    $attributes_query = "SELECT * FROM hostel_attributes WHERE hostel_id = ?";
    $attributes_stmt = $connection->prepare($attributes_query);
    $attributes_stmt->bind_param("i", $hostel_id);
    $attributes_stmt->execute();
    $attributes = $attributes_stmt->get_result();

    $is_eligible = true;
    while ($attr = $attributes->fetch_assoc()) {
        if ($attr['attribute_key'] === 'gender' && $attr['attribute_value'] !== $student_gender) {
            $is_eligible = false;
            break;
        }
        if ($attr['attribute_key'] === 'year_of_study' && $attr['attribute_value'] != $student_year) {
            $is_eligible = false;
            break;
        }
    }
    return $is_eligible;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Select Hostel - UR-HUYE</title>
    <link href="../icon1.png" rel="icon" type="image/x-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">
    <link href="../Dashboard/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Dashboard/assets/css/style.css" rel="stylesheet">
    <style>
        .hostel-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
            height: 100%;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .hostel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .room-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
        }
        .carousel-item {
            padding: 20px;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .hostel-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        .room-badge {
            font-size: 0.9em;
            margin: 5px;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 5%;
            background: rgba(0,0,0,0.1);
        }
        .room-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .room-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        .carousel-indicators {
            margin-bottom: 0;
        }
        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
        }
        .hostel-nav {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .hostel-nav button {
            margin: 0 5px;
            padding: 8px 15px;
            border-radius: 20px;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
        }
        .hostel-nav button.active {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .room-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .status-available {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-limited {
            background-color: #fff3cd;
            color: #856404;
        }
        .carousel-container {
            position: relative;
            padding: 20px 0;
        }
        .carousel-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
        }
        .application-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        .roommate-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .receipt-upload {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }
        .receipt-upload:hover {
            border-color: #0d6efd;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-center pb-0 fs-4">Hostel Application</h5>
                                    
                                    <?php if (isset($_SESSION['success_message'])): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <?php 
                                            echo $_SESSION['success_message'];
                                            unset($_SESSION['success_message']);
                                            ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($_SESSION['error_message'])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?php 
                                            echo $_SESSION['error_message'];
                                            unset($_SESSION['error_message']);
                                            ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($current_application): ?>
                                        <!-- Current Application Details -->
                                        <div class="application-card">
                                            <h4>Your Current Application</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Hostel:</strong> <?php echo htmlspecialchars($current_application['hostel_name']); ?></p>
                                                    <p><strong>Room:</strong> <?php echo htmlspecialchars($current_application['room_code']); ?></p>
                                                    <p><strong>Status:</strong> 
                                                        <span class="status-badge <?php echo $current_application['status'] === 'approved' ? 'status-approved' : 'status-pending'; ?>">
                                                            <?php echo ucfirst($current_application['status']); ?>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Hostel Fees:</strong> RWF 40,000</p>
                                                    <p><strong>Application Date:</strong> <?php echo date('F j, Y', strtotime($current_application['created_at'])); ?></p>
                                                </div>
                                            </div>

                                            <!-- SLEP Upload Section -->
                                            <div class="receipt-upload mt-4">
                                                <h5>SLEP Payment Receipt</h5>
                                                <p class="text-muted mb-3">Please upload your bank payment receipt for RWF 40,000</p>
                                                
                                                <?php if ($current_application['slep']): ?>
                                                    <div class="current-receipt mb-3">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6>Current Receipt</h6>
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-8">
                                                                        <img src="./uploads/receipts/<?php echo htmlspecialchars($current_application['slep']); ?>" 
                                                                             class="img-thumbnail" style="max-height: 150px;" 
                                                                             alt="Payment Receipt">
                                                                    </div>
                                                                    <div class="col-md-4 text-end">
                                                                        <a href="./uploads/receipts/<?php echo htmlspecialchars($current_application['slep']); ?>" 
                                                                           class="btn btn-sm btn-info" target="_blank">
                                                                           <i class="bi bi-eye"></i> View Full
                                                                        </a>
                                                                        <form action="delete_receipt.php" method="POST" class="d-inline">
                                                                            <input type="hidden" name="application_id" value="<?php echo $current_application['id']; ?>">
                                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                                    onclick="return confirm('Are you sure you want to delete this receipt?')">
                                                                                <i class="bi bi-trash"></i> Delete
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <form action="upload_receipt.php" method="POST" enctype="multipart/form-data" class="mt-3">
                                                    <input type="hidden" name="application_id" value="<?php echo $current_application['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="receipt" class="form-label">Upload New Receipt</label>
                                                        <input type="file" class="form-control" id="receipt" name="receipt" 
                                                               accept="image/*,.pdf" required>
                                                        <small class="text-muted">Accepted formats: JPG, PNG, PDF (Max size: 2MB)</small>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">
                                                        <?php echo $current_application['slep'] ? 'Update Receipt' : 'Upload Receipt'; ?>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Roommates Section -->
                                            <div class="mt-4">
                                                <h5>Your Roommates</h5>
                                                <?php
                                                $roommates_query = "SELECT s.*, a.status 
                                                                  FROM applications a
                                                                  JOIN info s ON a.regnumber = s.regnumber
                                                                  WHERE a.room_id = ? AND a.regnumber != ?";
                                                $roommates_stmt = $connection->prepare($roommates_query);
                                                $roommates_stmt->bind_param("is", $current_application['room_id'], $student_regnumber);
                                                $roommates_stmt->execute();
                                                $roommates = $roommates_stmt->get_result();
                                                
                                                if ($roommates->num_rows > 0):
                                                    while ($roommate = $roommates->fetch_assoc()):
                                                ?>
                                                    <div class="roommate-card">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-8">
                                                                <h6 class="mb-1"><?php echo htmlspecialchars($roommate['names']); ?></h6>
                                                                <p class="mb-0 text-muted"><?php echo htmlspecialchars($roommate['regnumber']); ?></p>
                                                              <p class="mb-0 text-muted"> <span class="text-dark fw-bold">college: </span> <?php echo htmlspecialchars($roommate['college']); ?></p>
                                                              <p class="mb-0 text-muted"> <span class="text-dark fw-bold">school: </span> <?php echo htmlspecialchars($roommate['school']); ?></p>
                                                              <p class="mb-0 text-muted"> <span class="text-dark fw-bold">year: </span> <?php echo htmlspecialchars($roommate['yearofstudy']); ?></p>  
                                                            </div>
                                                            <div class="col-md-4 text-end">
                                                                <span class="badge bg-success">Roommate</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php 
                                                    endwhile;
                                                else:
                                                ?>
                                                    <p class="text-muted">No roommates assigned yet.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Existing hostel selection interface -->
                                        <p class="text-center text-muted">Available hostels in <?php echo htmlspecialchars($student_campus); ?> campus</p>
                                        
                                        <?php if ($hostels->num_rows > 0): ?>
                                            <div class="carousel-container">
                                                <div id="hostelCarousel" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner p-6">
                                                        <?php 
                                                        $counter = 0;
                                                        $hostels->data_seek(0);
                                                        while ($hostel = $hostels->fetch_assoc()): 
                                                            $isActive = $counter === 0 ? 'active' : '';
                                                        ?>
                                                            <div class="carousel-item <?php echo $isActive; ?>">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="card hostel-card">
                                                                            <div class="card-body">
                                                                                <div class="hostel-info">
                                                                                    <h4 class="card-title"><?php echo htmlspecialchars($hostel['name']); ?></h4>
                                                                                    <p class="text-muted mb-0">Campus: <?php echo htmlspecialchars($hostel['campus_name']); ?></p>
                                                                                    <?php 
                                                                                    $is_eligible = checkHostelEligibility($connection, $hostel['id'], $student_gender, $student_year);
                                                                                    if (!$is_eligible): 
                                                                                    ?>
                                                                                        <div class="eligibility-info mt-2">
                                                                                            <span class="badge bg-danger">Not eligible for your profile</span>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                
                                                                                <?php
                                                                                // Get available rooms for this hostel
                                                                                $room_query = "SELECT r.*, 
                                                                                            (SELECT COUNT(*) FROM applications a WHERE a.room_id = r.id) as current_applications,
                                                                                            UNIX_TIMESTAMP() as data_timestamp
                                                                                            FROM rooms r 
                                                                                            WHERE r.hostel_id = ? AND r.remain > 0
                                                                                            FOR UPDATE";
                                                                                $room_stmt = $connection->prepare($room_query);
                                                                                $room_stmt->bind_param("i", $hostel['id']);
                                                                                $room_stmt->execute();
                                                                                $rooms = $room_stmt->get_result();
                                                                                ?>
                                                                                
                                                                                <div class="room-list">
                                                                                    <?php if ($rooms->num_rows > 0): ?>
                                                                                        <?php while ($room = $rooms->fetch_assoc()): ?>
                                                                                            <div class="room-item">
                                                                                                <div class="row align-items-center">
                                                                                                    <div class="col-md-3">
                                                                                                        <h6 class="mb-0">Room <?php echo htmlspecialchars($room['room_code']); ?></h6>
                                                                                                        <small class="text-muted">Last updated: <span class="room-timestamp" data-timestamp="<?php echo $room['data_timestamp']; ?>"></span></small>
                                                                                                    </div>
                                                                                                    <div class="col-md-3">
                                                                                                        <span class="room-status <?php echo $room['remain'] > 1 ? 'status-available' : 'status-limited'; ?>">
                                                                                                            <?php echo $room['remain']; ?> beds available
                                                                                                        </span>
                                                                                                        <?php if ($room['current_applications'] > 0): ?>
                                                                                                            <small class="text-muted d-block"><?php echo $room['current_applications']; ?> pending applications</small>
                                                                                                        <?php endif; ?>
                                                                                                    </div>
                                                                                                    <div class="col-md-6 text-end">
                                                                                                        <form action="apply_room.php" method="POST" class="d-inline">
                                                                                                            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                                                                                            <input type="hidden" name="hostel_id" value="<?php echo $hostel['id']; ?>">
                                                                                                            <input type="hidden" name="timestamp" value="<?php echo $room['data_timestamp']; ?>">
                                                                                                            <button type="submit" class="btn btn-primary" 
                                                                                                                    <?php echo ($room['remain'] <= 0 || !$is_eligible) ? 'disabled' : ''; ?>>
                                                                                                                <?php if ($room['remain'] <= 0): ?>
                                                                                                                    No Beds Available
                                                                                                                <?php elseif (!$is_eligible): ?>
                                                                                                                    Not Eligible
                                                                                                                <?php else: ?>
                                                                                                                    Apply Now
                                                                                                                <?php endif; ?>
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php endwhile; ?>
                                                                                    <?php else: ?>
                                                                                        <p class="text-muted text-center">No available rooms in this hostel</p>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php 
                                                        $counter++;
                                                        endwhile; 
                                                        ?>
                                                    </div>
                                                    <div class="carousel-indicators">
                                                        <?php for($i = 0; $i < $counter; $i++): ?>
                                                            <button type="button" data-bs-target="#hostelCarousel" data-bs-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?>></button>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <button class="carousel-control-prev bg-dark" type="button" data-bs-target="#hostelCarousel" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next bg-dark" type="button" data-bs-target="#hostelCarousel" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info">
                                                No hostels available in your campus at the moment.
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="../Dashboard/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functionality
        document.getElementById('roomTypeFilter').addEventListener('change', filterHostels);
        document.getElementById('availabilityFilter').addEventListener('change', filterHostels);
        document.getElementById('searchHostel').addEventListener('input', filterHostels);

        function filterHostels() {
            const roomType = document.getElementById('roomTypeFilter').value;
            const availability = document.getElementById('availabilityFilter').value;
            const searchText = document.getElementById('searchHostel').value.toLowerCase();

            // Add your filtering logic here
            // This is a placeholder for the actual filtering implementation
            console.log('Filtering:', { roomType, availability, searchText });
        }

        // Initialize carousel with custom settings
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = new bootstrap.Carousel(document.getElementById('hostelCarousel'), {
                interval: 5000,
                wrap: true,
                keyboard: true
            });
        });

        // Update timestamps every 30 seconds
        function updateTimestamps() {
            document.querySelectorAll('.room-timestamp').forEach(element => {
                const timestamp = parseInt(element.dataset.timestamp);
                const now = Math.floor(Date.now() / 1000);
                const diff = now - timestamp;
                
                if (diff < 60) {
                    element.textContent = 'just now';
                } else if (diff < 3600) {
                    element.textContent = Math.floor(diff / 60) + ' minutes ago';
                } else {
                    element.textContent = Math.floor(diff / 3600) + ' hours ago';
                }
            });
        }

        // Initial update
        updateTimestamps();
        // Update every 30 seconds
        setInterval(updateTimestamps, 30000);

        // Auto-refresh the page every 2 minutes to get fresh data
        setTimeout(() => {
            window.location.reload();
        }, 120000);
    </script>
</body>
</html>     