<?php
require_once 'db.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_log("Starting password update process");

$response = array();

try {
    if (!isset($_POST['user_id'])) {
        throw new Exception('No user ID provided');
    }

    $userId = $_POST['user_id'];
    $updates = array();

    // Handle profile picture upload if present
    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/PostProperAdmin/uploads/user_profile_pictures/';
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    if (isset($_FILES['profile_picture'])) {
        $file = $_FILES['profile_picture'];
        $fileName = time() . '_' . uniqid() . '.jpg';
        $filePath = $uploadPath . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $updates['user_profile_picture'] = 'uploads/user_profile_pictures/' . $fileName;
        }
    }

    // Handle other field updates
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $updates['username'] = $_POST['username'];
    }
    if (isset($_POST['adrHouseNo']) && !empty($_POST['adrHouseNo'])) {
        $updates['adrHouseNo'] = $_POST['adrHouseNo'];
    }
    if (isset($_POST['adrStreet']) && !empty($_POST['adrStreet'])) {
        $updates['adrStreet'] = $_POST['adrStreet'];
    }
    if (isset($_POST['adrZone']) && !empty($_POST['adrZone'])) {
        $updates['adrZone'] = $_POST['adrZone'];
    }

    // Handle password update with verification
    if (isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['currentPassword']) && !empty($_POST['currentPassword'])) {
        error_log("Attempting password update");
        
        // First verify the current password
        $stmt = $conn->prepare("SELECT password FROM user_accounts WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            error_log("User not found for ID: " . $userId);
            throw new Exception('User not found');
        }

        // Log password verification details (be careful with sensitive data in production)
        error_log("Stored password from DB: " . $user['password']);
        error_log("Current password from input: " . $_POST['currentPassword']);
        
        // Direct comparison of hashes
        if ($_POST['currentPassword'] !== $user['password']) {
            error_log("Password verification failed - Hashes don't match");
            throw new Exception('Current password is incorrect');
        }

        error_log("Password verification successful");
        // If verification passed, set the new password directly
        $updates['password'] = $_POST['password'];
    }

    if (!empty($updates)) {
        // Begin transaction
        $conn->beginTransaction();

        try {
            // Build and execute UPDATE query
            $sql = "UPDATE user_accounts SET ";
            $params = array();
            foreach ($updates as $key => $value) {
                $sql .= "$key = ?, ";
                $params[] = $value;
            }
            $sql = rtrim($sql, ', ');
            $sql .= " WHERE id = ?";
            $params[] = $userId;

            error_log("Update SQL: " . $sql);
            error_log("Update params (excluding sensitive data): " . count($params) . " parameters");

            $stmt = $conn->prepare($sql);
            if ($stmt->execute($params)) {
                // Fetch updated user data
                $selectStmt = $conn->prepare("SELECT * FROM user_accounts WHERE id = ?");
                $selectStmt->execute([$userId]);
                $user = $selectStmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $conn->commit();
                    $response['status'] = 'success';
                    $response['message'] = 'Profile updated successfully';
                    $response['user'] = array(
                        'id' => $user['id'],
                        'firstName' => $user['firstName'],
                        'lastName' => $user['lastName'],
                        'username' => $user['username'],
                        'age' => $user['age'],
                        'gender' => $user['gender'],
                        'adrHouseNo' => $user['adrHouseNo'],
                        'adrZone' => $user['adrZone'],
                        'adrStreet' => $user['adrStreet'],
                        'birthday' => $user['birthday'],
                        'user_profile_picture' => $user['user_profile_picture']
                    );
                } else {
                    throw new Exception('User not found after update');
                }
            } else {
                throw new Exception('Failed to update user data');
            }
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    } else {
        throw new Exception('No fields to update');
    }

} catch (Exception $e) {
    error_log("Error in update_user_profile: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

error_log("Final response: " . json_encode($response));
echo json_encode($response);
?>