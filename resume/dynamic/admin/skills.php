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
        $skill_name = trim($_POST['skill_name'] ?? '');
        $skill_level = trim($_POST['skill_level'] ?? '');
        
        $stmt = $pdo->prepare("INSERT INTO skills (skill_name, skill_level) VALUES (?, ?)");
        $stmt->execute([$skill_name, $skill_level]);
        $message = 'Skill added successfully!';
    }
    
    if ($action === 'update') {
        $id = $_POST['id'];
        $skill_name = trim($_POST['skill_name'] ?? '');
        $skill_level = trim($_POST['skill_level'] ?? '');
        
        $stmt = $pdo->prepare("UPDATE skills SET skill_name = ?, skill_level = ? WHERE id = ?");
        $stmt->execute([$skill_name, $skill_level, $id]);
        $message = 'Skill updated successfully!';
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Skill deleted successfully!';
    }
}

$skills = $pdo->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills</title>
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
            <a href="skills.php" class="active"><i class="fas fa-code"></i> <span>Skills</span></a>
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
                <div class="icon"><i class="fas fa-code"></i></div>
                <h2>Manage Skills</h2>
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
                <h2>Add New Skill</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="insert">
                    <div class="form-group">
                        <label>Skill Name</label>
                        <input type="text" name="skill_name" required>
                    </div>
                    <div class="form-group">
                        <label>Skill Level</label>
                        <select name="skill_level" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Expert">Expert</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Add Skill</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Skills List</h2>
                <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Skill Name</th>
                            <th>Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skills as $skill): ?>
                        <tr>
                            <td><?php echo $skill['id']; ?></td>
                            <td><?php echo htmlspecialchars($skill['skill_name']); ?></td>
                            <td><?php echo htmlspecialchars($skill['skill_level']); ?></td>
                            <td class="actions">
                                <button class="btn btn-primary" onclick="editSkill(<?php echo htmlspecialchars(json_encode($skill)); ?>)">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
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
            <h2>Edit Skill</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editId">
                <div class="form-group">
                    <label>Skill Name</label>
                    <input type="text" name="skill_name" id="editSkillName" required>
                </div>
                <div class="form-group">
                    <label>Skill Level</label>
                    <select name="skill_level" id="editSkillLevel" required>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Expert">Expert</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editSkill(skill) {
            document.getElementById('editId').value = skill.id;
            document.getElementById('editSkillName').value = skill.skill_name;
            document.getElementById('editSkillLevel').value = skill.skill_level;
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
            <a href="skills.php" class="active"><i class="fas fa-code"></i> Skills</a>
            <a href="projects.php"><i class="fas fa-diagram-project"></i> Projects</a>
            <a href="education.php"><i class="fas fa-graduation-cap"></i> Edu</a>
            <a href="../auth/logout.php" class="logout-link"><i class="fas fa-right-from-bracket"></i> Out</a>
        </div>
    </nav>
</body>
</html>
