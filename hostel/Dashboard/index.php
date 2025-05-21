<?php
include 'connection.php';

// Initialize statistics arrays
$stats = [
    'overall' => [
        'total_campuses' => 0,
        'total_hostels' => 0,
        'total_rooms' => 0,
        'total_beds' => 0,
        'occupied_beds' => 0,
        'available_beds' => 0,
        'total_applications' => 0,
        'pending_applications' => 0,
        'paid_applications' => 0,
        'applications_by_month' => [],
        'applications_by_status' => [],
        'room_status_distribution' => []
    ],
    'campuses' => [],
    'hostels' => []
];

try {
    // Get campus-level statistics
    $query = "SELECT 
                c.id as campus_id,
                c.name as campus_name,
                COUNT(DISTINCT h.id) as total_hostels,
                COUNT(DISTINCT r.id) as total_rooms,
                SUM(r.number_of_beds) as total_beds,
                SUM(r.remain) as available_beds,
                COUNT(DISTINCT a.id) as total_applications,
                SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_applications,
                SUM(CASE WHEN a.status = 'paid' THEN 1 ELSE 0 END) as paid_applications,
                SUM(CASE WHEN a.slep = 1 THEN 1 ELSE 0 END) as slep_applications
             FROM campuses c
             LEFT JOIN hostels h ON h.campus_id = c.id
             LEFT JOIN rooms r ON r.hostel_id = h.id
             LEFT JOIN applications a ON a.room_id = r.id
             GROUP BY c.id, c.name
             ORDER BY c.name";
    
    $result = $connection->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['occupied_beds'] = $row['total_beds'] - $row['available_beds'];
            $row['occupancy_rate'] = $row['total_beds'] > 0 ? 
                ($row['occupied_beds'] / $row['total_beds']) * 100 : 0;
            $stats['campuses'][] = $row;
            
            // Update overall stats
            $stats['overall']['total_campuses']++;
            $stats['overall']['total_hostels'] += $row['total_hostels'];
            $stats['overall']['total_rooms'] += $row['total_rooms'];
            $stats['overall']['total_beds'] += $row['total_beds'];
            $stats['overall']['occupied_beds'] += $row['occupied_beds'];
            $stats['overall']['total_applications'] += $row['total_applications'];
            $stats['overall']['pending_applications'] += $row['pending_applications'];
            $stats['overall']['paid_applications'] += $row['paid_applications'];
        }
    }

    // Get hostel-level statistics
    $query = "SELECT 
                h.id as hostel_id,
                h.name as hostel_name,
                c.name as campus_name,
                COUNT(DISTINCT r.id) as total_rooms,
                SUM(r.number_of_beds) as total_beds,
                SUM(r.remain) as available_beds,
                COUNT(DISTINCT a.id) as total_applications,
                SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_applications,
                SUM(CASE WHEN a.status = 'paid' THEN 1 ELSE 0 END) as paid_applications,
                SUM(CASE WHEN a.slep = 1 THEN 1 ELSE 0 END) as slep_applications
             FROM hostels h
             LEFT JOIN campuses c ON h.campus_id = c.id
             LEFT JOIN rooms r ON r.hostel_id = h.id
             LEFT JOIN applications a ON a.room_id = r.id
             GROUP BY h.id, h.name, c.name
             ORDER BY c.name, h.name";
    
    $result = $connection->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['occupied_beds'] = $row['total_beds'] - $row['available_beds'];
            $row['occupancy_rate'] = $row['total_beds'] > 0 ? 
                ($row['occupied_beds'] / $row['total_beds']) * 100 : 0;
            $stats['hostels'][] = $row;
        }
    }

    // Get application trends by month
    $query = "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN slep = 1 THEN 1 ELSE 0 END) as slep
             FROM applications
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY month DESC
             LIMIT 12";
    
    $result = $connection->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $stats['overall']['applications_by_month'][] = $row;
        }
    }

    // Get room status distribution
    $query = "SELECT 
                status,
                COUNT(*) as count
             FROM rooms
             GROUP BY status";
    
    $result = $connection->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $stats['overall']['room_status_distribution'][] = $row;
        }
    }

    // Calculate overall available beds
    $stats['overall']['available_beds'] = $stats['overall']['total_beds'] - $stats['overall']['occupied_beds'];

} catch (Exception $e) {
    error_log("Error getting statistics: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Statistics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: transform 0.2s;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 1rem;
            opacity: 0.8;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }
        .nav-tabs .nav-link {
            color: #495057;
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
            color: #0d6efd;
        }
        .occupancy-rate {
            font-weight: bold;
        }
        .occupancy-rate.high {
            color: #dc3545;
        }
        .occupancy-rate.medium {
            color: #ffc107;
        }
        .occupancy-rate.low {
            color: #28a745;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .trend-indicator {
            font-size: 0.8rem;
            margin-left: 5px;
        }
        .trend-up {
            color: #dc3545;
        }
        .trend-down {
            color: #28a745;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Hostel Statistics Dashboard</h2>
            <button class="btn btn-primary" onclick="refreshStats()">
                <i class="fas fa-sync-alt"></i> Refresh Statistics
            </button>
        </div>

        <!-- Overall Statistics Cards -->
        <div class="row mb-4">
            <!-- Total Campuses -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['overall']['total_campuses']); ?></div>
                        <div class="stat-label">Total Campuses</div>
                    </div>
                </div>
            </div>

            <!-- Total Hostels -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['overall']['total_hostels']); ?></div>
                        <div class="stat-label">Total Hostels</div>
                    </div>
                </div>
            </div>

            <!-- Total Applications -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['overall']['total_applications']); ?></div>
                        <div class="stat-label">Total Applications</div>
                        <small>Pending: <?php echo number_format($stats['overall']['pending_applications']); ?></small>
                    </div>
                </div>
            </div>

            <!-- Available Beds -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['overall']['available_beds']); ?></div>
                        <div class="stat-label">Available Beds</div>
                        <small>Total: <?php echo number_format($stats['overall']['total_beds']); ?></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="statTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview" role="tab">
                    Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="campuses-tab" data-bs-toggle="tab" href="#campuses" role="tab">
                    Campus Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hostels-tab" data-bs-toggle="tab" href="#hostels" role="tab">
                    Hostel Details
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="statTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <!-- Application Trends Chart -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Application Trends</h5>
                                <div class="chart-container">
                                    <canvas id="applicationTrendsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Status Distribution -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Room Status Distribution</h5>
                                <div class="chart-container">
                                    <canvas id="roomStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campus Distribution -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Campus Distribution</h5>
                                <div class="chart-container">
                                    <canvas id="campusDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Status -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Application Status</h5>
                                <div class="chart-container">
                                    <canvas id="applicationStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campus Details Tab -->
            <div class="tab-pane fade" id="campuses" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Campus Statistics</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Campus Name</th>
                                        <th>Hostels</th>
                                        <th>Rooms</th>
                                        <th>Total Beds</th>
                                        <th>Occupied</th>
                                        <th>Available</th>
                                        <th>Occupancy Rate</th>
                                        <th>Applications</th>
                                        <th>Pending</th>
                                        <th>Paid</th>
                                        <th>SLEP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['campuses'] as $campus): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($campus['campus_name']); ?></td>
                                        <td><?php echo number_format($campus['total_hostels']); ?></td>
                                        <td><?php echo number_format($campus['total_rooms']); ?></td>
                                        <td><?php echo number_format($campus['total_beds']); ?></td>
                                        <td><?php echo number_format($campus['occupied_beds']); ?></td>
                                        <td><?php echo number_format($campus['available_beds']); ?></td>
                                        <td>
                                            <span class="occupancy-rate <?php 
                                                echo $campus['occupancy_rate'] >= 90 ? 'high' : 
                                                    ($campus['occupancy_rate'] >= 70 ? 'medium' : 'low'); 
                                            ?>">
                                                <?php echo number_format($campus['occupancy_rate'], 1); ?>%
                                            </span>
                                        </td>
                                        <td><?php echo number_format($campus['total_applications']); ?></td>
                                        <td><?php echo number_format($campus['pending_applications']); ?></td>
                                        <td><?php echo number_format($campus['paid_applications']); ?></td>
                                        <td><?php echo number_format($campus['slep_applications']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hostel Details Tab -->
            <div class="tab-pane fade" id="hostels" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Hostel Statistics</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Campus</th>
                                        <th>Hostel Name</th>
                                        <th>Rooms</th>
                                        <th>Total Beds</th>
                                        <th>Occupied</th>
                                        <th>Available</th>
                                        <th>Occupancy Rate</th>
                                        <th>Applications</th>
                                        <th>Pending</th>
                                        <th>Paid</th>
                                        <th>SLEP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['hostels'] as $hostel): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($hostel['campus_name']); ?></td>
                                        <td><?php echo htmlspecialchars($hostel['hostel_name']); ?></td>
                                        <td><?php echo number_format($hostel['total_rooms']); ?></td>
                                        <td><?php echo number_format($hostel['total_beds']); ?></td>
                                        <td><?php echo number_format($hostel['occupied_beds']); ?></td>
                                        <td><?php echo number_format($hostel['available_beds']); ?></td>
                                        <td>
                                            <span class="occupancy-rate <?php 
                                                echo $hostel['occupancy_rate'] >= 90 ? 'high' : 
                                                    ($hostel['occupancy_rate'] >= 70 ? 'medium' : 'low'); 
                                            ?>">
                                                <?php echo number_format($hostel['occupancy_rate'], 1); ?>%
                                            </span>
                                        </td>
                                        <td><?php echo number_format($hostel['total_applications']); ?></td>
                                        <td><?php echo number_format($hostel['pending_applications']); ?></td>
                                        <td><?php echo number_format($hostel['paid_applications']); ?></td>
                                        <td><?php echo number_format($hostel['slep_applications']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Application Trends Chart
            new Chart(document.getElementById('applicationTrendsChart'), {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_column(array_reverse($stats['overall']['applications_by_month']), 'month')); ?>,
                    datasets: [{
                        label: 'Total Applications',
                        data: <?php echo json_encode(array_column(array_reverse($stats['overall']['applications_by_month']), 'total')); ?>,
                        borderColor: '#0d6efd',
                        tension: 0.1
                    }, {
                        label: 'Pending',
                        data: <?php echo json_encode(array_column(array_reverse($stats['overall']['applications_by_month']), 'pending')); ?>,
                        borderColor: '#ffc107',
                        tension: 0.1
                    }, {
                        label: 'Paid',
                        data: <?php echo json_encode(array_column(array_reverse($stats['overall']['applications_by_month']), 'paid')); ?>,
                        borderColor: '#28a745',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Room Status Chart
            new Chart(document.getElementById('roomStatusChart'), {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_column($stats['overall']['room_status_distribution'], 'status')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($stats['overall']['room_status_distribution'], 'count')); ?>,
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Campus Distribution Chart
            new Chart(document.getElementById('campusDistributionChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($stats['campuses'], 'campus_name')); ?>,
                    datasets: [{
                        label: 'Total Beds',
                        data: <?php echo json_encode(array_column($stats['campuses'], 'total_beds')); ?>,
                        backgroundColor: '#0d6efd'
                    }, {
                        label: 'Occupied Beds',
                        data: <?php echo json_encode(array_column($stats['campuses'], 'occupied_beds')); ?>,
                        backgroundColor: '#dc3545'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Application Status Chart
            new Chart(document.getElementById('applicationStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Paid', 'SLEP'],
                    datasets: [{
                        data: [
                            <?php echo $stats['overall']['pending_applications']; ?>,
                            <?php echo $stats['overall']['paid_applications']; ?>,
                            <?php 
                                $slep_total = 0;
                                foreach ($stats['campuses'] as $campus) {
                                    $slep_total += $campus['slep_applications'];
                                }
                                echo $slep_total;
                            ?>
                        ],
                        backgroundColor: ['#ffc107', '#28a745', '#0d6efd']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

        function refreshStats() {
            window.location.reload();
        }
    </script>
</body>
</html>