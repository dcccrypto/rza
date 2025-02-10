<?php
$error_code = $_SERVER['REDIRECT_STATUS'] ?? 404;
$error_messages = [
    403 => 'Access Forbidden',
    404 => 'Page Not Found',
    500 => 'Internal Server Error'
];
$error_message = $error_messages[$error_code] ?? 'Unknown Error';

require_once 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page py-5">
                <h1 class="display-1 text-danger mb-4"><?= $error_code ?></h1>
                <h2 class="h3 mb-4"><?= htmlspecialchars($error_message) ?></h2>
                <p class="lead mb-4">We apologize for the inconvenience. Please try one of the following:</p>
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Go Back
                    </a>
                    <a href="/rza/" class="btn btn-success">
                        <i class="bi bi-house"></i> Go Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 