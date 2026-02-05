<?php
requireLogin();
$currentUser = getUser();
$userModel = new User($db);
$user = $userModel->getById($currentUser['id'] ?? 0);
if (!$user) {
    setFlash('error', 'Account not found');
    redirect('?page=logout');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'update_profile') {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');

        if ($name === '' || $email === '') {
            setFlash('error', 'Name and email are required');
        } elseif (!isValidEmail($email)) {
            setFlash('error', 'Please enter a valid email');
        } elseif ($userModel->emailExistsForOther($email, $user['id'])) {
            setFlash('error', 'Email is already in use');
        } elseif ($userModel->updateProfile($user['id'], $name, $email, $phone)) {
            $updatedUser = $userModel->getById($user['id']);
            $token = issueAuthToken($updatedUser);
            setAuthCookie($token);
            setFlash('success', 'Profile updated');
            redirect('?page=profile');
        } else {
            setFlash('error', 'Profile update failed');
        }
    }

    if ($action === 'update_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword === '' || $confirmPassword === '') {
            setFlash('error', 'Please enter a new password');
        } elseif ($newPassword !== $confirmPassword) {
            setFlash('error', 'Passwords do not match');
        } elseif (strlen($newPassword) < 6) {
            setFlash('error', 'Password must be at least 6 characters');
        } elseif (!password_verify($currentPassword, $user['password'])) {
            setFlash('error', 'Current password is incorrect');
        } elseif ($userModel->updatePassword($user['id'], $newPassword)) {
            setFlash('success', 'Password updated');
            redirect('?page=profile');
        } else {
            setFlash('error', 'Password update failed');
        }
    }
}

$pageTitle = 'Profile - AngkorCam Tourism';
ob_start();
?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow dark">Account</p>
            <h1>Profile settings</h1>
            <p class="lead">Update your personal details and secure your account.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="profile-card">
                    <h4 class="mb-3">Personal information</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn btn-gradient">Save changes</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="profile-card">
                    <h4 class="mb-3">Change password</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="update_password">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-outline-gradient">Update password</button>
                    </form>
                </div>
                <div class="profile-card mt-4">
                    <h5>Support</h5>
                    <p class="text-muted mb-0">Need help with a booking? Reach us at hello@angkorcam.com.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../../layouts/main.php'; ?>
