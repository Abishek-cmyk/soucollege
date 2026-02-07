<?php
include "db.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sourashtra College</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/style.css">
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

<!-- ===== COLLEGE HEADER ===== -->
<header class="college-header">

    <!-- LEFT + CENTER -->
    <div class="college-center">

        <div class="college-left">
            <img src="images/logo.jpg" alt="College Logo">
        </div>

        <div class="college-text">
            <h1>SOURASHTRA COLLEGE (AUTONOMOUS)</h1>
            <h3>(A Linguistic Minority Co-Educational Institution)</h3>
            <p>
                Affiliated to Madurai Kamaraj University<br>
                Re-accredited with ‘A’ Grade by NAAC, Madurai – 625004
            </p>
        </div>

    </div>

    <!-- RIGHT PANEL -->
    <div class="college-right">

        <!-- CONTACT INFO -->
<!-- CONTACT INFO -->
<div class="contact-info">
    <span>
        <i class="bi bi-envelope-fill"></i>
        soucontact@sourashtracollege.ac.in
    </span>

    <span>
        <i class="bi bi-telephone-fill"></i>
        8754209994 / 8754208885
    </span>
</div>


        <!-- ADMIN BUTTON -->
        <a href="../college/academics/login.php" class="admin-link">Admin Login</a>

        <!-- SOCIAL ICONS -->
        <div class="social-icons">
            <a href="https://wa.me/918754209994" target="_blank"><i class="bi bi-whatsapp"></i></a>
            <a href="https://www.instagram.com/YOURPAGE" target="_blank"><i class="bi bi-instagram"></i></a>
            <a href="https://www.facebook.com/YOURPAGE" target="_blank"><i class="bi bi-facebook"></i></a>
            <a href="https://www.linkedin.com/company/YOURPAGE" target="_blank"><i class="bi bi-linkedin"></i></a>
        </div>

    </div>

</header>

<!-- ===== NAVIGATION ===== -->
<nav class="navbar">
    <ul>
        <li><a href="./index.php">HOME</a></li>

        <li><a href="#">ADMIN</a>
            <div class="dropdown">
                <a href="../College/scw/about.html">About Us</a>
                <a href="../College/coe/management.html">Management</a>
                <a href="../College/scw/organisation.html">Organisational Structure</a>
                <a href="../College/scw/principal.html">Principal's Message</a>
                <a href="../College/scw/deans.html">Dean</a>
                <a href="../College/scw/corevalue.html">Core Values</a>
                <a href="../College/coe/exam.html">Controller of Examinations</a>
                <a href="../College/scw/committees.html">Committee</a>
                <div class="dropdown-item">
                    <a href="#">Policies</a>
                    <div class="submenu">
                        <a href="../College/coe/rules.html">Rules and Regulations</a>
                        <a href="../College/scw/llp.html">Laboratory &amp; Library Policies</a>
                        <a href="#">UGC Compliance</a>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="../College/scw/code of condcut.html">Code of Conduct</a>
                    <div class="submenu">
                        <a href="../College/coe/ICC.pdf" target="_blank">ICC</a>
                    </div>
                </div>
                <a href="../College/coe/IDP.pdf" target="_blank">IDP</a>
            </div>
        </li>

        <li> <a href ="#">ACADEMICS</a>
            <div class="dropdown">
                <a href="../College/Academics/HTML/programmes.html">Programmes Offered</a>
                <a href="../College/Academics/admission.php">Admission Procedure</a>
                <div class="dropdown-item">
                    <a href="#">Fee Structure</a>
                    <div class="submenu">
                        <a href="../College/Academics/fee-structure-aided.php">Aided</a>
                        <a href="../College/Academics/fee-structure-self.php">Self Finance</a>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="#">Departments</a>
                    <div class="submenu">
                        <a href="../College/Academics/HTML/dept-aided.html">Aided</a>
                        <a href="../College/Academics/HTML/dept-self.html">Self Finance</a>
                    </div>
                </div>


                <div class="dropdown-item">
                    <a href="#">Programmes</a>
                    <div class="submenu">
                        <a href="../College/Academics/program-outcomes.php">Program Outcomes</a>
                        <ul class="inner-sub">
                <li><a href="../College/Academics/po-2019.php">2019–2020</a></li>
                <li><a href="../College/Academics/po-2020.php">2020–2021</a></li>
                <li><a href="../College/Academics/po-2021.php">2021–2022</a></li>
                <li><a href="../College/Academics/po-2022.php">2022–2023</a></li>
            </ul>
                        <a href="../College/Academics/value-based.php">Value Based-Professional Ethics</a>
                    </div>
                </div>


<div class="dropdown-item">
    <a href="#">Syllabus</a>

    <div class="submenu">
        <?php

        /* ================= YEARS ================= */
        $years = mysqli_query($conn,"
            SELECT * FROM syllabus_year
            ORDER BY is_default DESC, year_name DESC
        ");

        while ($y = mysqli_fetch_assoc($years)) {
        ?>
        <!-- YEAR -->
        <div class="dropdown-item">
            <a href="#"><?php echo $y['year_name']; ?></a>

            <div class="submenu">

                <?php
                /* ================= CHECK VOLUMES ================= */
                $volumes = mysqli_query($conn,"
                    SELECT * FROM syllabus_volume
                    WHERE year_id='{$y['id']}'
                ");

                /* ---------- IF VOLUME EXISTS ---------- */
                if (mysqli_num_rows($volumes) > 0) {

                    while ($v = mysqli_fetch_assoc($volumes)) {
                    ?>
                        <!-- VOLUME -->
                        <div class="dropdown-item">
                            <a href="#"><?php echo $v['volume_name']; ?></a>

                            <div class="submenu">
                                <?php
                                $subjects = mysqli_query($conn,"
                                    SELECT * FROM syllabus_subject
                                    WHERE volume_id='{$v['id']}'
                                ");

                                while ($s = mysqli_fetch_assoc($subjects)) {
                                ?>
                                    <a href="uploads/syllabus/<?php echo $s['pdf_file']; ?>" target="_blank">
                                        <?php echo $s['subject_name']; ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php
                    }

                }
                ?>

                <?php
                /* ---------- WITHOUT VOLUME (DIRECT SUBJECT) ---------- */
                $yearSubjects = mysqli_query($conn,"
                    SELECT * FROM syllabus_subject
                    WHERE year_id='{$y['id']}' AND volume_id IS NULL
                ");

                while ($s = mysqli_fetch_assoc($yearSubjects)) {
                ?>
                    <a href="uploads/syllabus/<?php echo $s['pdf_file']; ?>" target="_blank">
                        <?php echo $s['subject_name']; ?>
                    </a>
                <?php } ?>

            </div>
        </div>
        <?php } ?>
    </div>
</div>



                
                <div class="dropdown-item">
                    <a href="#">HandBook</a>
                    <div class="submenu">
                        <a href="../College/Academics/handbook-aided.php">Aided</a>
                        <a href="../College/Academics/handbook-self.php">Self</a>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="../College/Academics/HTML/anti-ragging.html">Anti-RaggingCell</a>
                </div>
                <div class="dropdown-item">
                    <a href="#">List of Medals</a>
                    <div class="submenu">
                        <a href="../College/Academics/medals_aided.php">Aided</a>
                        <a href="../College/Academics/medals_self.php">Self</a>
                    </div>
                </div>
            </div>
        </li>

       <li> <a href ="#">STUDENTS</a>
            <div class="dropdown">
                <a href="../college/web/scholarship.html">Scholarships & Endowment</a>
                <a href="../college/web/pta.html">Parent-Teacher Association</a>
                <a href="#">Certificate - Procedures</a>
                <a href="../college/web/grievance.html">Students Grievance Form</a>
                <a href="../college/web/Cguidence.html">Career Guidance Cell</a>
                <a href="#">Newsletter</a>
                <a href="#">Students Services</a>
                <div class="dropdown-item">
                    <a href="../college/web/feedbackform.html">Feedback</a>
                    <div class="submenu">
                        <a href="#">Feedback Form</a>
                        <a href="../college/web/parentfeedbackform.html">Parent Feedback Form</a>
                        <a href="#">Feedback Analysis Result 2017-2018</a>
                        <a href="#">Feedback Analysis Result 2018-2019</a>
                        <a href="#">Feedback Analysis Result 2019-2020</a>
                        <a href="#">Feedback Analysis Result 2021-2022</a>
                        <a href="#">Feedback Analysis Result 2022-2023</a>
                        <a href="#">Feedback Analysis Result 2023-2024</a>
                    </div>
                </div>
                <a href="#">Parents Feedback</a>
            </div>
        </li>

        <li> <a href ="#"> LIBRARY</a>
            <div class="dropdown">
               <div class="dropdown-item">
                    <a href="#">Library Details</a>
                    <div class="submenu">
                        <a href="#">Printed Book Table</a>
                    </div>
                </div>
                <a href="#">N-List</a>
                <a href="#">E-Library</a>
                <a href="#">OPAC</a>
                <a href="#">E-Resources</a>
            </div>
        </li>

        <li> <a href ="#"> FACILITIES</a>
            <div class="dropdown">
                <a href="#">Hostels</a>
                <a href="#">Infrastructure</a>
                <a href="#">Gallery</a>
                <a href="#">LMS</a>
                <a href="#">Capability and Schemes Enchancement Department</a>
            </div>
        </li>

        <li> <a href ="#">PLACEMENT</a>
            <div class="dropdown">
                <a href="../college/CW/placement/2po/po.html">Placement Officers</a>
                <a href="../college/CW/placement/3po/pr.html">Placement</a>
                <div class="dropdown-item">
                    <a href="../college/CW/placement/4po/pg.html">Gallery</a>
        </li>

        <li> <a href ="../college/CW/alumini/soon.html">ALUMNI</a>
            <div class="dropdown">
                <a href="../college/CW/alumini/soon.html"">Alumni (SCAAN)</a>
                <a href="../college/CW/alumini/soon.html"">Activities</a>
                <a href="../college/CW/alumini/soon.html"">Registration</a>
                <a href="../college/CW/alumini/soon.html"">Feedback</a>
                <a href="../college/CW/alumini/soon.html"">Feedback 2.7</a>
            </div>
        </li>

        <li> <a href ="#">PROFESSIONAL COURSES</a>
            <div class="dropdown">
                <a href="#">MBA</a>
                <a href="#">MCA</a>
                <a href="#">AICTE</a>
            </div>
        </li>

        <li> <a href ="#">RESEARCH</a>
            <div class="dropdown">
                <div class="dropdown-item">
                    <a href="#">RDC</a>
                    <div class="submenu">
                        <a href="#">Rules and Regulations</a>
                        <a href="#">Laboratory &amp; Library Policies</a>
                        <a href="#">UGC Compliance</a>
                    </div>
                </div>
                <a href="#">IPR</a>
                <div class="dropdown-item">
                    <a href="#">Ph.D</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
                <a href="#">M.Phil</a>
                <a href="#">Research Promotion Policy</a>
            </div>
        </li>

        <li> <a href ="#">NAAC</a>
            <div class="dropdown">
                <a href="#">SSR -4 th Cycle</a>
                <a href="#">NAAC- 4th CYCLE SUPPORTING DOCUMENTS</a>
            </div>
        </li>

        <li> <a href ="#">IQAC+</a>
            <div class="dropdown">
                <a href="#">Composition</a>
                <a href="#">AQAR 23-24</a>
                <a href="#">Procedures And Policies</a>
                <a href="#">Vision And Mission</a>
                <a href="#">Functions</a>
                <div class="dropdown-item">
                    <a href="#">NIRF</a>
                    <div class="submenu">
                        <a href="#">Rules and Regulations</a>
                        <a href="#">Laboratory &amp; Library Policies</a>
                        <a href="#">UGC Compliance</a>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="#">AISHE</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
                <a href="#">Reports</a>
                <div class="dropdown-item">
                    <a href="#">Minutes</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
                <a href="#">Photos</a>
                <div class="dropdown-item">
                    <a href="#">Best Practices</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="#">Distinctiveness</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
                <a href="#">Journals</a>
                <div class="dropdown-item">
                    <a href="#">Code of Conduct</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="#">Supporting Document</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="#">Magazine</a>
                    <div class="submenu">
                        <a href="#">ICC</a>
                    </div>
                </div>
            </div>
        </li>

        <li><a href="#">RESULT</a></li>
        <li><a href="#">RTI</a></li>
        <li><a href="#">CONTACT US</a></li>
    </ul>
</nav>

<!-- ===== HEADER SLIDER ===== -->
<div class="slider">
    <div class="slides">
        <?php
        $header = mysqli_query($conn,"SELECT * FROM header_images ORDER BY id DESC");
        while($h = mysqli_fetch_assoc($header)){
        ?>
            <img src="uploads/header/<?php echo $h['image']; ?>" class="slide">
        <?php } ?>
    </div>

    <!-- Navigation buttons -->
    <span class="prev" onclick="moveSlide(-1)">&#10094;</span>
    <span class="next" onclick="moveSlide(1)">&#10095;</span>
</div>

<!-- WELCOME SECTION -->
<section class="welcome-section reveal">

    <div class="golden-text">CELEBRATING GOLDEN JUBILEE YEAR</div>

    <h2>WELCOME TO SOURASHTRA COLLEGE</h2>
    <div class="heading-line"></div>

    <p>ICT Academy – NPTEL Local Chapter</p>

    <!-- NEW COURSES -->
    <h3>NEW COURSE INTRODUCED</h3>
    <?php
    $courses = mysqli_query($conn, "SELECT * FROM welcome_updates WHERE type='course'");
    while ($c = mysqli_fetch_assoc($courses)) {
    ?>
        <div class="course-box">
            <?= htmlspecialchars($c['title']) ?>
            <span class="new-badge">NEW</span>
        </div>
    <?php } ?>

    <br>

    <!-- ADMISSION -->
    <?php
    $ad = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM welcome_updates WHERE type='admission' LIMIT 1")
    );
    ?>
    <?php if ($ad) { ?>
        <a href="<?= htmlspecialchars($ad['link'] ?? '#') ?>" class="admission-btn">
            <?= htmlspecialchars($ad['title']) ?> <?= htmlspecialchars($ad['year']) ?>
        </a>
    <?php } ?>

    <br><br>

    <div class="fee-text">
        Gateway For Payment Of ONLINE FEE
    </div>

    <br>
    <a href="#" class="fee-link">Click here to Pay Online Fee</a>

</section>

<?php
include "db.php";

$circular = mysqli_query($conn,
"SELECT * FROM news_events WHERE type='circular' ORDER BY created_at DESC");

$news = mysqli_query($conn,
"SELECT * FROM news_events WHERE type='news' ORDER BY created_at DESC");
?>

<!-- ===== CIRCULAR & NEWS ===== -->
<div class="updates-section reveal-left">
<div class="updates-grid">

<!-- ===== CIRCULAR COLUMN ===== -->
<div class="updates-column">
<h2>Circular</h2>

<?php while($row = mysqli_fetch_assoc($circular)){ ?>
<div class="update-item">
<h3><?= htmlspecialchars($row['title']) ?></h3>
<p><?= nl2br(htmlspecialchars($row['content'])) ?></p>

<?php if($row['file_link']){ ?>
<a href="<?= htmlspecialchars($row['file_link']) ?>" target="_blank">View PDF</a>
<?php } elseif($row['url']){ ?>
<a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">View Link</a>
<?php } ?>

</div>
<?php } ?>
</div>

<!-- ===== NEWS COLUMN ===== -->
<div class="updates-column">
<h2>News & Events</h2>

<?php while($row = mysqli_fetch_assoc($news)){ ?>
<div class="update-item">
<h3><?= htmlspecialchars($row['title']) ?></h3>
<p><?= nl2br(htmlspecialchars($row['content'])) ?></p>

<?php if($row['file_link']){ ?>
<a href="<?= htmlspecialchars($row['file_link']) ?>" target="_blank">View PDF</a>
<?php } elseif($row['url']){ ?>
<a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">View Link</a>
<?php } ?>

</div>
<?php } ?>
</div>

</div>
</div>

<!-- ===== GALLERY SECTION ===== -->
<section>
    <h2>Photo Gallery</h2>

    <div class="gallery reveal">
    <?php
    $gallery = mysqli_query($conn,"SELECT * FROM gallery ORDER BY id DESC");
    while($g = mysqli_fetch_assoc($gallery)){
    ?>
        <div class="gallery-box reveal-zoom delay-1">
            <img src="uploads/gallery/<?php echo $g['image']; ?>" alt="Gallery Image">
            <p><?php echo $g['title']; ?></p>
        </div>
    <?php
    }
    ?>
    </div>
</section>

<!-- ===== VISION / MISSION / CAMPUS ===== -->
<section class="vmc-section reveal-right">

    <div class="vmc-box delay-1">
        <i class="bi bi-eye-fill vmc-icon"></i>
        <h2>VISION</h2>
        <p>
            We envisage the steady progress of the grass 
            roots of the society in general, and to uplift 
            the economically, socially and educationally backward 
            Sourashtra youth in particular, by providing a conducive 
            environment to prepare for their successful take-off 
            in their careers and also in real life as responsible citizens of India.
        </p>
    </div>

    <div class="vmc-box delay-2">
        <i class="bi bi-flag-fill vmc-icon"></i>
        <h2>MISSION</h2>
        <p>
            Our objective is to work as an ensemble with a commitment to inspire 
            our students to acquire physical strength, intellectual curiosity and
            moral integrity. Most importantly we strive to inculcate an attitude 
            for service to build a mutually supportive, communally co-operative and 
            religiously tolerant society.
        </p>
    </div>

    <div class="vmc-box delay-3">
        <i class="bi bi-building vmc-icon"></i>
        <h2>CAMPUS DRIVE</h2>
        <p>
            Career Guidance Cell and Placement Cell are the dynamic bodies 
            initiated to cater the needs of students in order to promote 
            higher studies and further motivate, train and guide students 
            for higher studies, competitive examinations and employment.
        </p>
    </div>

</section>

<!-- ===== FOOTER ===== -->
<div class="footer reveal">
    <div class="footer-row">

        <!-- LEFT -->
        <div class="footer-left">
            <h3>Have a question?</h3>

            <p>
                Vilachery Main Road,<br>
                Pasumalai (P.O),<br>
                Madurai – 625 004
            </p>

            <p>
                Phone: 8754209994 / 8754208885
            </p>
        </div>

        <!-- CENTER -->
        <div class="footer-center">
            <p><strong>Website:</strong><br> www.sourashtracollege.com</p>

            <p>
                © 2025 Powered by Sourashtra College <br>
            </p>
        </div>

        <!-- RIGHT -->
        <div class="footer-right">
    <h3 class="mb-3">Follow Us</h3>

    <a href="https://wa.me/918754209994" target="_blank" class="social-icon">
        <i class="bi bi-whatsapp"></i>
    </a>

    <a href="https://www.instagram.com/YOURPAGE" target="_blank" class="social-icon">
        <i class="bi bi-instagram"></i>
    </a>

    <a href="https://www.facebook.com/YOURPAGE" target="_blank" class="social-icon">
        <i class="bi bi-facebook"></i>
    </a>

    <a href="https://www.linkedin.com/company/YOURPAGE" target="_blank" class="social-icon">
        <i class="bi bi-linkedin"></i>
    </a>
</div>

        </div>

    </div>
</div>

<script>
let currentIndex = 0;
const slides = document.querySelectorAll(".slide");
const slidesContainer = document.querySelector(".slides");
const totalSlides = slides.length;

function showSlide(index){
    if(index >= totalSlides){
        currentIndex = 0;
    }else if(index < 0){
        currentIndex = totalSlides - 1;
    }else{
        currentIndex = index;
    }

    slidesContainer.style.transform =
        "translateX(" + (-currentIndex * 100) + "%)";
}

function moveSlide(step){
    showSlide(currentIndex + step);
}

/* AUTO SLIDE */
setInterval(() => {
    moveSlide(1);
}, 7000); // 7 seconds

const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');

window.addEventListener('scroll', () => {

    reveals.forEach(el => {
        const windowHeight = window.innerHeight;
        const elementTop = el.getBoundingClientRect().top;
        const revealPoint = 120;

        if(elementTop < windowHeight - revealPoint){
            el.classList.add('show');
        }
    });

});

</script>

</body>
</html>
