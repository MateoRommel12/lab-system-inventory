<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Helpers.php';

// Initialize Auth
$auth = Auth::getInstance();

// Check if user is admin, redirect if not
if (!$auth->isAdmin()) {
    header('Location: ../access-denied.php');
    exit;
}

// Set page title
$pageTitle = "View Room";
$currentPage = 'rooms';

// Include header
require_once __DIR__ . '/../includes/header.php';

// Include required models
require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/../models/Equipment.php';

// Initialize models
$roomModel = new Room();
$equipmentModel = new Equipment();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    Helpers::redirectWithMessage("rooms.php", "Invalid room ID", "danger");
    exit;
}

$roomId = (int)$_GET['id'];

// Get room details
$room = $roomModel->getRoomWithTechnician($roomId);

if (!$room) {
    Helpers::redirectWithMessage("rooms.php", "Room not found", "danger");
    exit;
}

// Get equipment in this room
$equipment = $equipmentModel->getAllEquipment('', '', $roomId);
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>
            <i class="fas fa-door-open me-2"></i>Room Details
        </h1>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="rooms.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Rooms
        </a>
    </div>
</div>

<div class="row">
    <!-- Room Information -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Room Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px;">Room Name:</th>
                        <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Building:</th>
                        <td><?php echo htmlspecialchars($room['building']); ?></td>
                    </tr>
                    <tr>
                        <th>Floor:</th>
                        <td><?php echo htmlspecialchars($room['floor']); ?></td>
                    </tr>
                    <tr>
                        <th>Room Number:</th>
                        <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                    </tr>
                    <tr>
                        <th>Capacity:</th>
                        <td><?php echo $room['capacity'] ?: 'Not specified'; ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?php 
                            $statusClass = [
                                'active' => 'success',
                                'inactive' => 'danger',
                                'under maintenance' => 'warning'
                            ];
                            $class = $statusClass[$room['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $class; ?>">
                                <?php echo ucfirst($room['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td><?php echo nl2br(htmlspecialchars($room['description'] ?? '')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Equipment List -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Equipment in this Room</h5>
                <a href="../equipment/index.php?room=<?php echo $roomId; ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-laptop me-2"></i>View All Equipment
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($equipment)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No equipment assigned to this room.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equipment as $item): ?>
                            <tr>
                                <td><?php echo $item['equipment_id']; ?></td>
                                <td><?php echo htmlspecialchars($item['name'] ?? ''); ?></td>
                                <td>
                                    <?php 
                                    $statusClass = [
                                        'available' => 'success',
                                        'in_use' => 'warning',
                                        'maintenance' => 'danger'
                                    ];
                                    $class = $statusClass[$item['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $class; ?>">
                                        <?php echo ucfirst($item['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="../equipment/view.php?id=<?php echo $item['equipment_id']; ?>" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?> 