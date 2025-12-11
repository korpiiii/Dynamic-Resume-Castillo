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

$profileImage = null;
foreach ($abouts as $about) {
    if (!empty($about['image'])) {
        $profileImage = $about['image'];
        break;
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="John Harvy R. Castillo - Frontend Developer & Customer Service Professional. View my portfolio, skills, and projects.">
    <title>John Harvy R. Castillo | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #ec4899;
            --dark: #0f172a;
            --text: #475569;
            --text-light: #94a3b8;
            --light: #f1f5f9;
            --lighter: #f8fafc;
            --white: #ffffff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
            --shadow: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background: var(--lighter);
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }

        ul {
            list-style: none;
        }

        header {
            background: var(--gradient);
            color: var(--white);
            padding: 48px 20px 56px;
            text-align: center;
        }

        .header-content {
            max-width: 700px;
            margin: 0 auto;
        }

        .profile-header-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.9);
            margin-bottom: 16px;
            object-fit: cover;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }

        header .tagline {
            font-size: 0.95rem;
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 16px;
        }

        .contact-header {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .contact-header a {
            color: var(--white);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            padding: 6px 12px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            font-weight: 500;
        }

        .contact-header a:hover {
            background: rgba(255,255,255,0.25);
        }

        nav {
            background: var(--white);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        nav ul {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        nav a {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text);
            padding: 14px 16px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        nav a:hover {
            color: var(--primary);
            background: var(--lighter);
        }

        main {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px 16px 40px;
        }

        .section {
            background: var(--white);
            margin-bottom: 20px;
            padding: 28px 24px;
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .section h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section h2 i {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient);
            color: var(--white);
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .about-content {
            display: flex;
            gap: 24px;
            align-items: flex-start;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-light);
            flex-shrink: 0;
        }

        .about-text p {
            margin-bottom: 12px;
            color: var(--text);
            font-size: 0.95rem;
        }

        .about-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 16px;
        }

        .about-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            background: var(--lighter);
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .about-item i {
            color: var(--primary);
            font-size: 1rem;
            width: 20px;
        }

        .about-item strong {
            color: var(--dark);
        }

        .skills-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .skill-category {
            background: var(--lighter);
            padding: 20px;
            border-radius: 12px;
        }

        .skill-category h3 {
            color: var(--dark);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .skill-category h3 i {
            color: var(--primary);
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 10px;
        }

        .skill-card {
            background: var(--white);
            padding: 14px;
            border-radius: 10px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .skill-card h4 {
            color: var(--dark);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .skill-card .level {
            display: inline-block;
            padding: 3px 10px;
            background: var(--gradient);
            color: var(--white);
            font-size: 0.7rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .project-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .project-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .project-placeholder {
            height: 160px;
            background: var(--gradient);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--white);
            padding: 20px;
            text-align: center;
        }

        .project-placeholder i {
            font-size: 2.5rem;
            opacity: 0.9;
        }

        .project-placeholder span {
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0.95;
        }

        .project-content {
            padding: 18px;
        }

        .project-card h3 {
            color: var(--dark);
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .project-card p {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .tech-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tech-tag {
            padding: 4px 10px;
            background: var(--lighter);
            color: var(--primary);
            font-size: 0.75rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .project-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            padding: 8px 16px;
            background: var(--gradient);
            color: var(--white);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .timeline {
            position: relative;
            padding-left: 24px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            background: var(--gradient);
            border-radius: 3px;
        }

        .timeline-item {
            position: relative;
            padding: 18px 20px;
            background: var(--lighter);
            border-radius: 12px;
            margin-bottom: 16px;
            margin-left: 16px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 22px;
            width: 14px;
            height: 14px;
            background: var(--primary);
            border-radius: 50%;
            border: 3px solid var(--white);
        }

        .timeline-item h3 {
            color: var(--dark);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .timeline-item .institution {
            color: var(--primary);
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .timeline-item .year {
            display: inline-block;
            padding: 4px 12px;
            background: var(--gradient);
            color: var(--white);
            font-size: 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            margin-top: 8px;
        }

        .timeline-item .cert-img {
            max-width: 100px;
            margin-top: 12px;
            border-radius: 6px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .contact-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            background: var(--lighter);
            border-radius: 12px;
        }

        .contact-card i {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient);
            color: var(--white);
            border-radius: 10px;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .contact-card .contact-info h4 {
            color: var(--text-light);
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .contact-card .contact-info p,
        .contact-card .contact-info a {
            color: var(--dark);
            font-size: 0.9rem;
            font-weight: 500;
        }

        footer {
            background: var(--dark);
            color: var(--white);
            text-align: center;
            padding: 24px 20px;
        }

        footer p {
            opacity: 0.7;
            font-size: 0.85rem;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .about-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .about-details {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .projects-grid {
                grid-template-columns: 1fr;
            }

            header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <?php if ($profileImage && file_exists('uploads/' . $profileImage)): ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('uploads/' . $profileImage)); ?>" alt="John Harvy Castillo" class="profile-header-img">
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
        </ul>
    </nav>

    <main>
        <section id="about" class="section">
            <h2><i class="fas fa-user"></i> About Me</h2>
            <?php if (empty($abouts)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-circle"></i>
                    <p>No about information added yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($abouts as $about): ?>
                <div class="about-content">
                    <?php if ($about['image'] && file_exists('uploads/' . $about['image'])): ?>
                        <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('uploads/' . $about['image'])); ?>" alt="Profile Photo" class="profile-img">
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
                    <p>No skills added yet.</p>
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
                    <p>No projects added yet.</p>
                </div>
            <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                    <div class="project-card">
                        <?php if ($project['image'] && file_exists('uploads/' . $project['image'])): ?>
                            <div class="project-image">
                                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('uploads/' . $project['image'])); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
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
                    <p>No education records added yet.</p>
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
                        <?php if ($edu['certificate_image'] && file_exists('uploads/' . $edu['certificate_image'])): ?>
                            <br><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('uploads/' . $edu['certificate_image'])); ?>" alt="Certificate" class="cert-img">
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
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> John Harvy R. Castillo. All rights reserved.</p>
    </footer>
</body>
</html>
<?php
$html = ob_get_clean();

header('Content-Type: text/html');
header('Content-Disposition: attachment; filename="resume_static.html"');
header('Content-Length: ' . strlen($html));

echo $html;
exit;
