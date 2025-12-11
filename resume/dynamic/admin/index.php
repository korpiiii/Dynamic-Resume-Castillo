<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

$aboutCount = $pdo->query("SELECT COUNT(*) FROM about")->fetchColumn();
$skillsCount = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$projectsCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$educationCount = $pdo->query("SELECT COUNT(*) FROM education")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <a href="index.php" class="admin-sidebar-brand">
                <div class="admin-sidebar-logo">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <h1>Portfolio</h1>
                    <span>Management</span>
                </div>
            </a>
        </div>
        <nav class="admin-sidebar-nav">
            <a href="index.php" class="active"><i class="fas fa-gauge-high"></i> <span>Dashboard</span></a>
            <a href="about.php"><i class="fas fa-user"></i> <span>About</span></a>
            <a href="skills.php"><i class="fas fa-code"></i> <span>Skills</span></a>
            <a href="projects.php"><i class="fas fa-diagram-project"></i> <span>Projects</span></a>
            <a href="education.php"><i class="fas fa-graduation-cap"></i> <span>Education</span></a>
            <div class="nav-divider"></div>
            <a href="../index.php"><i class="fas fa-eye"></i> <span>View Site</span></a>
            <a href="../auth/logout.php" class="logout-link"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a>
        </nav>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-title">
                <div class="icon"><i class="fas fa-gauge-high"></i></div>
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            </div>
            <div class="admin-topbar-actions">
                <a href="../index.php" class="admin-topbar-btn"><i class="fas fa-eye"></i> View Site</a>
            </div>
        </header>

        <div class="admin-content">
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>About</h3>
                    <div class="count"><?php echo $aboutCount; ?></div>
                    <a href="about.php"><i class="fas fa-arrow-right"></i> Manage</a>
                </div>
                <div class="dashboard-card">
                    <h3>Skills</h3>
                    <div class="count"><?php echo $skillsCount; ?></div>
                    <a href="skills.php"><i class="fas fa-arrow-right"></i> Manage</a>
                </div>
                <div class="dashboard-card">
                    <h3>Projects</h3>
                    <div class="count"><?php echo $projectsCount; ?></div>
                    <a href="projects.php"><i class="fas fa-arrow-right"></i> Manage</a>
                </div>
                <div class="dashboard-card">
                    <h3>Education</h3>
                    <div class="count"><?php echo $educationCount; ?></div>
                    <a href="education.php"><i class="fas fa-arrow-right"></i> Manage</a>
                </div>
            </div>
        </div>
    </main>

    <nav class="admin-mobile-nav">
        <div class="admin-mobile-nav-inner">
            <a href="index.php" class="active"><i class="fas fa-gauge-high"></i> Home</a>
            <a href="about.php"><i class="fas fa-user"></i> About</a>
            <a href="skills.php"><i class="fas fa-code"></i> Skills</a>
            <a href="projects.php"><i class="fas fa-diagram-project"></i> Projects</a>
            <a href="education.php"><i class="fas fa-graduation-cap"></i> Edu</a>
            <a href="../auth/logout.php" class="logout-link"><i class="fas fa-right-from-bracket"></i> Out</a>
        </div>
    </nav>
</body>
</html>
