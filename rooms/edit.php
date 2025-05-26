<?php
require_once '../config/config.php';
require_once '../utils/Helpers.php';
require_once '../utils/Auth.php';

// Initialize Auth
$auth = Auth::getInstance();

// Set page title
$pageTitle = "Edit Room";

// Include header
require_once '../includes/header.php';

// Require login
$auth->requireLogin();

// Check if user has permission to manage rooms
if (!$auth->canManageRooms()) {
    Helpers::redirectWithMessage("../index.php", "You don't have permission to edit rooms.", "danger");
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    Helpers::redirectWithMessage("index.php", "Invalid room ID", "danger");
    exit;
}

$roomId = (int)$_GET['id'];

// Include required models
require_once '../models/Room.php';
require_once '../models/User.php';

// Initialize models
$roomModel = new Room();
$userModel = new User();

// Get room details
$room = $roomModel->getRoomById($roomId);

if (!$room) {
    Helpers::redirectWithMessage("index.php", "Room not found", "danger");
    exit;
}

// Get all technicians for dropdown
$technicians = $userModel->getUsersByRole('lab_technician');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $updatedRoom = [
        'room_id' => $roomId,
        'room_name' => trim($_POST['room_name']),
        'building' => trim($_POST['building']),
        'floor' => trim($_POST['floor']),
        'room_number' => trim($_POST['room_number']),
        'capacity' => !empty($_POST['capacity']) ? (int)$_POST['capacity'] : null,
        'technician_id' => !empty($_POST['technician_id']) ? (int)$_POST['technician_id'] : null,
        'status' => trim($_POST['status']),
        'description' => trim($_POST['description'])
    ];
    
    // Validation
    $errors = [];
    
    if (empty($updatedRoom['room_name'])) {
        $errors[] = "Room name is required";
    }
    
    if (empty($updatedRoom['building'])) {
        $errors[] = "Building is required";
    }
    
    if (empty($updatedRoom['room_number'])) {
        $errors[] = "Room number is required";
    }
    
    // If no errors, update room
    if (empty($errors)) {
        $result = $roomModel->updateRoom($updatedRoom);
        
        if ($result) {
            // Log the action
            Helpers::logAction("Updated room: " . $updatedRoom['room_name']);
            
            // Redirect to room view with success message
            Helpers::redirectWithMessage("view.php?id=$roomId", "Room updated successfully", "success");
            exit;
        } else {
            $errors[] = "Failed to update room. Please try again.";
        }
    }
} else {
    // Pre-fill form with existing room data
    $updatedRoom = $room;
}
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>
            <i class="fas fa-edit me-2"></i>Edit Room
        </h1>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="view.php?id=<?php echo $roomId; ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Room Details
        </a>
    </div>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
        <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Room Information</h5>
    </div>
    <div class="card-body">
        <form action="edit.php?id=<?php echo $roomId; ?>" method="POST" class="row g-3">
            <!-- Basic Information -->
            <div class="col-md-6">
                <label for="room_name" class="form-label">Room Name *</label>
                <input type="text" class="form-control" id="room_name" name="room_name" required
                    value="<?php echo htmlspecialchars($updatedRoom['room_name']); ?>">
            </div>
            
            <div class="col-md-6">
                <label for="building" class="form-label">Building *</label>
                <input type="text" class="form-control" id="building" name="building" required
                    value="<?php echo htmlspecialchars($updatedRoom['building']); ?>">
            </div>
            
            <div class="col-md-4">
                <label for="floor" class="form-label">Floor</label>
                <input type="text" class="form-control" id="floor" name="floor" 
                    value="<?php echo htmlspecialchars($updatedRoom['floor']); ?>">
            </div>
            
            <div class="col-md-4">
                <label for="room_number" class="form-label">Room Number *</label>
                <input type="text" class="form-control" id="room_number" name="room_number" required
                    value="<?php echo htmlspecialchars($updatedRoom['room_number']); ?>">
            </div>
            
            <div class="col-md-4">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" 
                    value="<?php echo htmlspecialchars($updatedRoom['capacity']); ?>">
            </div>
            
            <!-- Status and Technician -->
            <div class="col-md-6">
                <label for="status" class="form-label">Status *</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" <?php echo $updatedRoom['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $updatedRoom['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="under maintenance" <?php echo $updatedRoom['status'] == 'under maintenance' ? 'selected' : ''; ?>>Under Maintenance</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="technician_id" class="form-label">Assigned Technician</label>
                <select class="form-select" id="technician_id" name="technician_id">
                    <option value="">No Technician Assigned</option>
                    <?php foreach ($technicians as $technician): ?>
                    <option value="<?php echo $technician['user_id']; ?>" 
                        <?php echo $updatedRoom['technician_id'] == $technician['user_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($technician['first_name'] . ' ' . $technician['last_name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Description -->
            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($updatedRoom['description']); ?></textarea>
            </div>
            
            <div class="col-12 mt-4">
                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Room
                </button>
                <a href="view.php?id=<?php echo $roomId; ?>" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?> 