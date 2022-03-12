<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo isset($page_title) ? ($page_title . ' - ') : ''; ?>Администрация</title>
        <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
        <script src="/admin/js/bootstrap.bundle.min.js"></script>
        <style>
            body > main {
                margin-bottom: 50px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/admin/home.php"><img src="/images/logo.png" alt="" height="24" class="d-inline-block align-text-top"></a>
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse" id="navbarCollapse" style="">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($active_page_home) && $active_page_home ? 'active' : ''; ?>" aria-current="page" href="/admin/home.php">Начало</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($active_page_websites) && $active_page_websites ? 'active' : ''; ?>" href="/admin/websites.php">Сайтове</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <div class="btn-group">
                            <a href="/" class="btn btn-outline-dark" target="_blank">Сайт</a>
                            <a href="/admin/logout.php" class="btn btn-outline-dark">Изход</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <main class="flex-shrink-0">
            <div class="container mt-3">