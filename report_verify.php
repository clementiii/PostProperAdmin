<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $report_id = $_GET['id'];

    try {
        $sql = "SELECT id, title, description, incident_picture FROM incident_reports WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $report_id, PDO::PARAM_INT);
        $stmt->execute();
        $report = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$report) {
            echo "Report not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "Invalid report ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/report_verify.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <script src="js/report_verify.js" defer></script>
    <link rel="icon" type="image/png" href="assets/Southside.png">
    
    <style>
        .modal-backdrop {
    display: none !important;
}

        .horizontal-images {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding: 10px 0;
        }
        .horizontal-images img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
        .zoomable-image {
            transition: transform 0.3s ease;
        }
        .zoomable-image:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <?php 
        $pageTitle = "Report Verification";
        include 'header.php';
        include 'sidebar.php';
    ?>

    <!-- Main content container -->
    <div class="main-container" style="padding-top: 20px;">
        <!-- Back Button with Font Awesome icon -->
        <button class="back-btn btn-secondary" onclick="goBack()">
            <i class="fas fa-arrow-left"></i> Back
        </button>

        <div class="form-group">
            <label for="reportTitle" class="form-label">Title:</label>
            <input type="text" class="form-control" id="reportTitle" value="<?php echo htmlspecialchars($report['title']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="reportDescription" class="form-label">Description:</label>
            <textarea class="form-control" id="reportDescription" readonly><?php echo htmlspecialchars($report['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Images:</label>
            <div class="horizontal-images">
                <?php
                if (!empty($report['incident_picture'])) {
                    // Decode the JSON string to an array
                    $images = json_decode($report['incident_picture'], true);
                    if ($images && is_array($images)) {
                        foreach ($images as $image) {
                            // Remove any unwanted whitespace
                            $image = trim($image);
                            echo '<img src="' . htmlspecialchars($image) . '" class="img-thumbnail zoomable-image" alt="Incident Image" onclick="showImageModal(this.src)">';
                        }
                    }
                }
                ?>
            </div>
        </div>

        <div class="d-flex justify-content-start" style="padding-top: 1rem;">
            <button class="action-btn me-2" id="resolvedBtn">Resolved</button>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Zoomed Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Resolution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this report as resolved?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmResolve" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       class ModalManager {
    constructor() {
        this.imageModal = new bootstrap.Modal(document.getElementById('imageModal'), {});
        this.confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), {});
        this.modalImage = document.getElementById('modalImage');
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Image modal listeners
        document.querySelectorAll('.zoomable-image').forEach(image => {
            image.addEventListener('click', () => this.showImageModal(image.src));
        });

        // Resolve button listener
        document.getElementById('resolvedBtn').addEventListener('click', () => {
            this.cleanupModalState();
            this.confirmModal.show();
        });

        // Confirm resolution button listener
        document.getElementById('confirmResolve').addEventListener('click', () => this.handleReportResolution());

        // Modal hidden cleanup
        ['imageModal', 'confirmModal'].forEach(modalId => {
            document.getElementById(modalId).addEventListener('hidden.bs.modal', () => this.cleanupModalState());
        });
    }

    showImageModal(src) {
        this.modalImage.src = src;
        this.cleanupModalState(); // Cleanup lingering artifacts
        this.imageModal.show();
    }

    async handleReportResolution() {
        const reportId = this.getReportId();
        try {
            const response = await fetch('resolve_report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `report_id=${reportId}`
            });

            if (response.ok) {
                this.confirmModal.hide();
                alert('The report has been marked as resolved.');
                window.history.back();
            } else {
                throw new Error(await response.text());
            }
        } catch (error) {
            alert(`Error: ${error.message}`);
        }
    }

    cleanupModalState() {
        // Remove all backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());

        // Ensure body classes are cleaned up
        document.body.classList.remove('modal-open');
        document.body.style.overflow = ''; // Reset overflow
        document.body.style.paddingRight = ''; // Reset padding
    }

    getReportId() {
        return <?php echo json_encode($report_id); ?>;
    }
}

// Textarea Auto-resize Manager
class TextareaManager {
    constructor(textareaId) {
        this.textarea = document.getElementById(textareaId);
        if (this.textarea) {
            this.setupAutoResize();
        }
    }

    setupAutoResize() {
        this.adjustHeight();
        this.textarea.addEventListener('input', () => this.adjustHeight());
    }

    adjustHeight() {
        this.textarea.style.height = 'auto';
        this.textarea.style.height = `${this.textarea.scrollHeight}px`;
    }
}

// Navigation Functions
const navigationManager = {
    goBack() {
        window.history.back();
    }
};

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ModalManager();
    new TextareaManager('reportDescription');
});

// Expose navigation function globally
window.goBack = navigationManager.goBack;
    </script>
</body>
</html>