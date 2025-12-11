<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'insert') {
        $school = trim($_POST['school'] ?? '');
        $degree = trim($_POST['degree'] ?? '');
        $year = trim($_POST['year'] ?? '');
        $certificate_image = '';
        
        if (!empty($_FILES['certificate_image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
            $ext = strtolower(pathinfo($_FILES['certificate_image']['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($_FILES['certificate_image']['tmp_name']);
            if (in_array($ext, $allowed) && in_array($mime, $allowedMimes)) {
                $filename = 'cert_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['certificate_image']['tmp_name'], '../uploads/' . $filename);
                $certificate_image = $filename;
            }
        }
        
        $stmt = $pdo->prepare("INSERT INTO education (school, degree, year, certificate_image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$school, $degree, $year, $certificate_image]);
        $message = 'Education added successfully!';
    }
    
    if ($action === 'update') {
        $id = $_POST['id'];
        $school = trim($_POST['school'] ?? '');
        $degree = trim($_POST['degree'] ?? '');
        $year = trim($_POST['year'] ?? '');
        
        if (!empty($_FILES['certificate_image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
            $ext = strtolower(pathinfo($_FILES['certificate_image']['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($_FILES['certificate_image']['tmp_name']);
            if (in_array($ext, $allowed) && in_array($mime, $allowedMimes)) {
                $filename = 'cert_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['certificate_image']['tmp_name'], '../uploads/' . $filename);
                $stmt = $pdo->prepare("UPDATE education SET school = ?, degree = ?, year = ?, certificate_image = ? WHERE id = ?");
                $stmt->execute([$school, $degree, $year, $filename, $id]);
            }
        } else {
            $stmt = $pdo->prepare("UPDATE education SET school = ?, degree = ?, year = ? WHERE id = ?");
            $stmt->execute([$school, $degree, $year, $id]);
        }
        $message = 'Education updated successfully!';
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM education WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Education deleted successfully!';
    }
}

$educations = $pdo->query("SELECT * FROM education ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Education</title>
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
            <a href="index.php"><i class="fas fa-gauge-high"></i> <span>Dashboard</span></a>
            <a href="about.php"><i class="fas fa-user"></i> <span>About</span></a>
            <a href="skills.php"><i class="fas fa-code"></i> <span>Skills</span></a>
            <a href="projects.php"><i class="fas fa-diagram-project"></i> <span>Projects</span></a>
            <a href="education.php" class="active"><i class="fas fa-graduation-cap"></i> <span>Education</span></a>
            <div class="nav-divider"></div>
            <a href="../index.php"><i class="fas fa-eye"></i> <span>View Site</span></a>
            <a href="../auth/logout.php" class="logout-link"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a>
        </nav>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-title">
                <div class="icon"><i class="fas fa-graduation-cap"></i></div>
                <h2>Manage Education</h2>
            </div>
            <div class="admin-topbar-actions">
                <a href="../index.php" class="admin-topbar-btn"><i class="fas fa-eye"></i> View Site</a>
            </div>
        </header>

        <div class="admin-content">
            <?php if ($message): ?>
                <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <div class="card">
                <h2>Add New Education</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insert">
                    <div class="form-group">
                        <label>School</label>
                        <input type="text" name="school" required>
                    </div>
                    <div class="form-group">
                        <label>Degree / Program</label>
                        <input type="text" name="degree" required>
                    </div>
                    <div class="form-group">
                        <label>Year</label>
                        <input type="text" name="year" placeholder="2020 - 2024">
                    </div>
                    <div class="form-group">
                        <label>Certificate Image</label>
                        <input type="file" name="certificate_image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success">Add Education</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Education List</h2>
                <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>School</th>
                            <th>Degree</th>
                            <th>Year</th>
                            <th>Certificate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($educations as $edu): ?>
                        <tr>
                            <td><?php echo $edu['id']; ?></td>
                            <td><?php echo htmlspecialchars($edu['school']); ?></td>
                            <td><?php echo htmlspecialchars($edu['degree']); ?></td>
                            <td><?php echo htmlspecialchars($edu['year']); ?></td>
                            <td>
                                <?php if ($edu['certificate_image']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($edu['certificate_image']); ?>" class="thumbnail">
                                <?php else: ?>
                                    No image
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <button class="btn btn-primary" onclick="editEducation(<?php echo htmlspecialchars(json_encode($edu)); ?>)">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $edu['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </main>

    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <h2>Edit Education</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editId">
                <div class="form-group">
                    <label>School</label>
                    <input type="text" name="school" id="editSchool" required>
                </div>
                <div class="form-group">
                    <label>Degree / Program</label>
                    <input type="text" name="degree" id="editDegree" required>
                </div>
                <div class="form-group">
                    <label>Year</label>
                    <input type="text" name="year" id="editYear">
                </div>
                <div class="form-group">
                    <label>Certificate Image (leave empty to keep current)</label>
                    <input type="file" name="certificate_image" accept="image/*">
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editEducation(edu) {
            document.getElementById('editId').value = edu.id;
            document.getElementById('editSchool').value = edu.school;
            document.getElementById('editDegree').value = edu.degree;
            document.getElementById('editYear').value = edu.year || '';
            const modal = document.getElementById('editModal');
            modal.style.display = 'block';
            setTimeout(() => modal.classList.add('show'), 10);
        }
        function closeModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 250);
        }
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>

    <nav class="admin-mobile-nav">
        <div class="admin-mobile-nav-inner">
            <a href="index.php"><i class="fas fa-gauge-high"></i> Home</a>
            <a href="about.php"><i class="fas fa-user"></i> About</a>
            <a href="skills.php"><i class="fas fa-code"></i> Skills</a>
            <a href="projects.php"><i class="fas fa-diagram-project"></i> Projects</a>
            <a href="education.php" class="active"><i class="fas fa-graduation-cap"></i> Edu</a>
            <a href="../auth/logout.php" class="logout-link"><i class="fas fa-right-from-bracket"></i> Out</a>
        </div>
    </nav>
</body>
</html>
