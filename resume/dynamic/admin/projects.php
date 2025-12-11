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
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $tech_used = trim($_POST['tech_used'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $image = '';
        
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($_FILES['image']['tmp_name']);
            if (in_array($ext, $allowed) && in_array($mime, $allowedMimes)) {
                $filename = 'project_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename);
                $image = $filename;
            }
        }
        
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, tech_used, image, link) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $tech_used, $image, $link]);
        $message = 'Project added successfully!';
    }
    
    if ($action === 'update') {
        $id = $_POST['id'];
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $tech_used = trim($_POST['tech_used'] ?? '');
        $link = trim($_POST['link'] ?? '');
        
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($_FILES['image']['tmp_name']);
            if (in_array($ext, $allowed) && in_array($mime, $allowedMimes)) {
                $filename = 'project_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename);
                $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, tech_used = ?, image = ?, link = ? WHERE id = ?");
                $stmt->execute([$title, $description, $tech_used, $filename, $link, $id]);
            }
        } else {
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, tech_used = ?, link = ? WHERE id = ?");
            $stmt->execute([$title, $description, $tech_used, $link, $id]);
        }
        $message = 'Project updated successfully!';
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Project deleted successfully!';
    }
}

$projects = $pdo->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
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
            <a href="projects.php" class="active"><i class="fas fa-diagram-project"></i> <span>Projects</span></a>
            <a href="education.php"><i class="fas fa-graduation-cap"></i> <span>Education</span></a>
            <div class="nav-divider"></div>
            <a href="../index.php"><i class="fas fa-eye"></i> <span>View Site</span></a>
            <a href="../auth/logout.php" class="logout-link"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a>
        </nav>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-title">
                <div class="icon"><i class="fas fa-diagram-project"></i></div>
                <h2>Manage Projects</h2>
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
                <h2>Add New Project</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insert">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Technologies Used</label>
                        <input type="text" name="tech_used" placeholder="PHP, MySQL, JavaScript">
                    </div>
                    <div class="form-group">
                        <label>Project Link (URL)</label>
                        <input type="url" name="link" placeholder="https://github.com/username/project">
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success">Add Project</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Projects List</h2>
                <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Technologies</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?php echo $project['id']; ?></td>
                            <td><?php echo htmlspecialchars($project['title']); ?></td>
                            <td><?php echo htmlspecialchars($project['tech_used']); ?></td>
                            <td>
                                <?php if ($project['image']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($project['image']); ?>" class="thumbnail">
                                <?php else: ?>
                                    No image
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <button class="btn btn-primary" onclick="editProject(<?php echo htmlspecialchars(json_encode($project)); ?>)">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
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
            <h2>Edit Project</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editId">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="editTitle" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editDescription"></textarea>
                </div>
                <div class="form-group">
                    <label>Technologies Used</label>
                    <input type="text" name="tech_used" id="editTechUsed">
                </div>
                <div class="form-group">
                    <label>Project Link (URL)</label>
                    <input type="url" name="link" id="editLink" placeholder="https://github.com/username/project">
                </div>
                <div class="form-group">
                    <label>Image (leave empty to keep current)</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editProject(project) {
            document.getElementById('editId').value = project.id;
            document.getElementById('editTitle').value = project.title;
            document.getElementById('editDescription').value = project.description || '';
            document.getElementById('editTechUsed').value = project.tech_used || '';
            document.getElementById('editLink').value = project.link || '';
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
            <a href="projects.php" class="active"><i class="fas fa-diagram-project"></i> Projects</a>
            <a href="education.php"><i class="fas fa-graduation-cap"></i> Edu</a>
            <a href="../auth/logout.php" class="logout-link"><i class="fas fa-right-from-bracket"></i> Out</a>
        </div>
    </nav>
</body>
</html>
