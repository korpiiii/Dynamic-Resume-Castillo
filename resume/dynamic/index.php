<?php
require_once 'config/db.php';

$abouts = $pdo->query("SELECT * FROM about ORDER BY id DESC")->fetchAll();
$skills = $pdo->query("SELECT * FROM skills ORDER BY id ASC")->fetchAll();
$projects = $pdo->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll();
$educations = $pdo->query("SELECT * FROM education ORDER BY id DESC")->fetchAll();

$skillCategories = [
    'Programming Languages' => [],
    'Frameworks & Libraries' => [],
    'Tools & Technologies' => [],
    'Soft Skills' => []
];

foreach ($skills as $skill) {
    $level = strtolower($skill['skill_level']);
    $name = strtolower($skill['skill_name']);
    
    if (in_array($name, ['html', 'css', 'javascript', 'php', 'python', 'java', 'c++', 'c#', 'ruby', 'go', 'typescript', 'sql'])) {
        $skillCategories['Programming Languages'][] = $skill;
    } elseif (in_array($name, ['react', 'vue', 'angular', 'node.js', 'express', 'laravel', 'django', 'flask', 'bootstrap', 'tailwind css', 'jquery'])) {
        $skillCategories['Frameworks & Libraries'][] = $skill;
    } elseif (in_array($name, ['git', 'github', 'vs code', 'docker', 'aws', 'linux', 'mysql', 'postgresql', 'mongodb', 'github pages'])) {
        $skillCategories['Tools & Technologies'][] = $skill;
    } else {
        $skillCategories['Soft Skills'][] = $skill;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="John Harvy R. Castillo - Frontend Developer & Customer Service Professional. View my portfolio, skills, and projects.">
    <title>John Harvy R. Castillo | Portfolio</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-content">
            <?php 
            $profileImage = null;
            foreach ($abouts as $about) {
                if (!empty($about['image'])) {
                    $profileImage = $about['image'];
                    break;
                }
            }
            ?>
            <?php if ($profileImage): ?>
                <img src="uploads/<?php echo htmlspecialchars($profileImage); ?>" alt="John Harvy Castillo" class="profile-header-img">
            <?php else: ?>
                <img src="https://avatars.githubusercontent.com/u/167641185?v=4" alt="John Harvy Castillo" class="profile-header-img">
            <?php endif; ?>
            <h1>John Harvy R. Castillo</h1>
            <p class="tagline">Frontend Developer | Customer Service Professional</p>
            <div class="contact-header">
                <a href="mailto:johnharvycastillo@gmail.com"><i class="fas fa-envelope"></i> johnharvycastillo@gmail.com</a>
                <a href="tel:+639499165930"><i class="fas fa-phone"></i> (+63) 949-916-5930</a>
                <a href="https://github.com/korpiiii" target="_blank"><i class="fab fa-github"></i> github.com/korpiiii</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> Bacoor City, Cavite, PH</a>
            </div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="#about"><i class="fas fa-user"></i> About</a></li>
            <li><a href="#skills"><i class="fas fa-code"></i> Skills</a></li>
            <li><a href="#projects"><i class="fas fa-project-diagram"></i> Projects</a></li>
            <li><a href="#education"><i class="fas fa-graduation-cap"></i> Education</a></li>
            <li><a href="#contact"><i class="fas fa-envelope"></i> Contact</a></li>
            <li><a href="auth/login.php"><i class="fas fa-lock"></i> Edit</a></li>
        </ul>
    </nav>

    <main>
        <section id="about" class="section">
            <h2><i class="fas fa-user"></i> About Me</h2>
            <?php if (empty($abouts)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-circle"></i>
                    <p>No about information added yet. Login to add your information.</p>
                </div>
            <?php else: ?>
                <?php foreach ($abouts as $about): ?>
                <div class="about-content">
                    <?php if ($about['image']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($about['image']); ?>" alt="Profile Photo" class="profile-img">
                    <?php else: ?>
                        <img src="https://avatars.githubusercontent.com/u/167641185?v=4" alt="Profile Photo" class="profile-img">
                    <?php endif; ?>
                    <div class="about-text">
                        <p><?php echo nl2br(htmlspecialchars($about['content'])); ?></p>
                        <div class="about-details">
                            <div class="about-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><strong>Location:</strong> Bacoor City, Cavite, 4102 (PH)</span>
                            </div>
                            <div class="about-item">
                                <i class="fas fa-language"></i>
                                <span><strong>Languages:</strong> Filipino (Fluent), English (Fluent)</span>
                            </div>
                            <div class="about-item">
                                <i class="fas fa-briefcase"></i>
                                <span><strong>Status:</strong> Open to Opportunities</span>
                            </div>
                            <div class="about-item">
                                <i class="fas fa-laptop-code"></i>
                                <span><strong>Focus:</strong> Frontend Development</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section id="skills" class="section">
            <h2><i class="fas fa-code"></i> Technical Skills</h2>
            <?php if (empty($skills)): ?>
                <div class="empty-state">
                    <i class="fas fa-tools"></i>
                    <p>No skills added yet. Login to add your skills.</p>
                </div>
            <?php else: ?>
                <div class="skills-container">
                    <?php foreach ($skillCategories as $category => $categorySkills): ?>
                        <?php if (!empty($categorySkills)): ?>
                        <div class="skill-category">
                            <h3>
                                <?php 
                                $icons = [
                                    'Programming Languages' => 'fas fa-code',
                                    'Frameworks & Libraries' => 'fas fa-layer-group',
                                    'Tools & Technologies' => 'fas fa-tools',
                                    'Soft Skills' => 'fas fa-brain'
                                ];
                                ?>
                                <i class="<?php echo $icons[$category] ?? 'fas fa-star'; ?>"></i>
                                <?php echo htmlspecialchars($category); ?>
                            </h3>
                            <div class="skills-grid">
                                <?php foreach ($categorySkills as $skill): ?>
                                <div class="skill-card">
                                    <h4><?php echo htmlspecialchars($skill['skill_name']); ?></h4>
                                    <span class="level"><?php echo htmlspecialchars($skill['skill_level']); ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="projects" class="section">
            <h2><i class="fas fa-project-diagram"></i> Projects</h2>
            <?php if (empty($projects)): ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>No projects added yet. Login to add your projects.</p>
                </div>
            <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                    <div class="project-card">
                        <?php if ($project['image']): ?>
                            <div class="project-image">
                                <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                            </div>
                        <?php else: ?>
                            <div class="project-placeholder">
                                <i class="fas fa-code"></i>
                                <span><?php echo htmlspecialchars($project['title']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="project-content">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>
                            <?php if ($project['tech_used']): ?>
                                <div class="tech-tags">
                                    <?php 
                                    $techs = explode('|', str_replace(',', '|', $project['tech_used']));
                                    foreach ($techs as $tech): 
                                        $tech = trim($tech);
                                        if ($tech):
                                    ?>
                                        <span class="tech-tag"><?php echo htmlspecialchars($tech); ?></span>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($project['link'])): ?>
                                <a href="<?php echo htmlspecialchars($project['link']); ?>" target="_blank" class="project-link">
                                    <i class="fas fa-external-link-alt"></i> View Project
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="education" class="section">
            <h2><i class="fas fa-graduation-cap"></i> Education & Certifications</h2>
            <?php if (empty($educations)): ?>
                <div class="empty-state">
                    <i class="fas fa-university"></i>
                    <p>No education records added yet. Login to add your education.</p>
                </div>
            <?php else: ?>
                <div class="timeline">
                    <?php foreach ($educations as $edu): ?>
                    <div class="timeline-item">
                        <h3><?php echo htmlspecialchars($edu['degree']); ?></h3>
                        <p class="institution"><i class="fas fa-university"></i> <?php echo htmlspecialchars($edu['school']); ?></p>
                        <?php if ($edu['year']): ?>
                            <span class="year"><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($edu['year']); ?></span>
                        <?php endif; ?>
                        <?php if ($edu['certificate_image']): ?>
                            <br><img src="uploads/<?php echo htmlspecialchars($edu['certificate_image']); ?>" alt="Certificate" class="cert-img">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="contact" class="section">
            <h2><i class="fas fa-envelope"></i> Contact Me</h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <i class="fas fa-envelope"></i>
                    <div class="contact-info">
                        <h4>Email</h4>
                        <a href="mailto:johnharvycastillo@gmail.com">johnharvycastillo@gmail.com</a>
                    </div>
                </div>
                <div class="contact-card">
                    <i class="fas fa-phone"></i>
                    <div class="contact-info">
                        <h4>Phone</h4>
                        <a href="tel:+639499165930">(+63) 949-916-5930</a>
                    </div>
                </div>
                <div class="contact-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="contact-info">
                        <h4>Location</h4>
                        <p>Bacoor City, Cavite, 4102 (PH)</p>
                    </div>
                </div>
                <div class="contact-card">
                    <i class="fab fa-github"></i>
                    <div class="contact-info">
                        <h4>GitHub</h4>
                        <a href="https://github.com/korpiiii" target="_blank">github.com/korpiiii</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section download-section">
            <h2><i class="fas fa-download"></i> Download Resume</h2>
            <p>Get a static HTML version of this resume that you can open anywhere, even offline!</p>
            <a href="download_static.php" class="download-btn-large"><i class="fas fa-download"></i> Download HTML Version</a>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> John Harvy R. Castillo. All rights reserved.</p>
    </footer>
</body>
</html>
