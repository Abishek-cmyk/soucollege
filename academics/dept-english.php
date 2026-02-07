<?php
// Include database connection (Line 2)
include_once 'db.php';

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch faculty members (Data is fetched here once)
$faculty = mysqli_query($conn, "SELECT * FROM `faculty_members` ORDER BY id ASC");
if (!$faculty) {
    die("Query failed: " . mysqli_error($conn));
}

// DEBUG: Optional - check if any rows fetched
// if(mysqli_num_rows($faculty) == 0){
//     // Comment this line if you want to show page even without faculty
//     // die("No faculty data found in the database.");
// }
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Department of English - Viewer</title>
  <link rel="stylesheet" href="./CSS/dept-eng.css" />
</head>
<body>
  <header class="site-header">
    <div class="wrap">
      <h1 class="title">Department of English</h1>
      <p class="subtitle">History of English Department</p>
    </div>
  </header>

  <main class="wrap main-grid">
    <section class="intro card">
      <h3>PROGRAMME OFFERED : B.A. English. (Aided), M.A. English. (Aided), M.Phil. (Self-finance)</h3>
      <p>The history of the department begins along with the inception of the college as the study of English is mandatory and very important. B.A. English course was started in 1972 to cater the demands of the students. The department reached a milestone in 1982 with the commencement of Post-graduate course. M.Phil English was started in 2004 for the benefit of research aspirants. The department is home to more than 3000 students. The alumni of the department are well placed in various private and public sectors. The department maintains a library that has more than 3500 books for the benefit of the students and scholars of English.</p>
      <p>The English Language Lab is equipped with Globarena Software (a Language Learning Software) to provide practice to students in an interactive way to develop the language skills – Listening, Speaking, Reading and Writing.</p>
      <p>Parnassus, an inter-collegiate annual literary and cultural fiesta is organized in a grand manner to help students shed their inhibitions and gain confidence.</p>
      <p>Caerus, an intra-collegiate competition is conducted for our college students to showcase their hidden talents and aid them to shape their mind.</p>
      <div class="ribbon">Functioning Since 1972</div>
    </section>

    <aside class="overview-card card">
      <h2>OVERVIEW</h2>
      <div class="sidebar">
        <button class="tab-btn active" data-target="vision">VISION & MISSION</button>
        <button class="tab-btn" data-target="eligibility">ELIGIBILITY AND ADMISSION</button>
        <button class="tab-btn" data-target="duration">DURATION</button>
        <button class="tab-btn" data-target="coursecontent">COURSE CONTENT</button>
        <button class="tab-btn" data-target="certification">CERTIFICATION COURSE</button>
        <button class="tab-btn" data-target="futureplan">FUTURE PLAN</button>
      </div>
      <div class="tab-content">
        <div id="vision" class="tab-panel active">
          <h3>VISION &amp; MISSION</h3>
          <strong>VISION :</strong>
          <ul>
            <li>Literature makes a man humane. To make every student a noble and enlightened is the important mission of the department.</li>
            <li>To help the students get the best placements both in public and private sectors to serve the society.</li>
          </ul>
          <strong>MISSION :</strong>
          <p><em>UG</em></p>
          <ul>
            <li>To make the students communicate in English effectively</li>
            <li>To make the students understand English literature with its social and historical background</li>
            <li>To make the students think analytically with the help of literature</li>
          </ul>
          <p><em>PG</em></p>
          <ul>
            <li>To give the students a holistic view of English literature, criticism and theory</li>
            <li>To make the students understand literature with its social and historical background</li>
            <li>To enable the students pursue research in English</li>
          </ul>
          <p><em>M.Phil</em></p>
          <ul>
            <li>To develop the skills and understanding necessary to undertake and pursue research at higher level</li>
            <li>To provide a strong foundation for a career in research besides career in teaching</li>
          </ul>
        </div>

        <div id="eligibility" class="tab-panel">
          <h3>ELIGIBILITY AND ADMISSION</h3>
          <p>Eligible candidates are those who have successfully passed out of the Higher Secondary Examination, conducted by the Government of TamilNadu or any other examination accepted by the syndicate as equivalent.</p>
        </div>

        <div id="duration" class="tab-panel">
          <h3>DURATION</h3>
          <p><strong>B.A. English. (Aided)</strong> - The duration of the course shall be three academic years comprising six semesters with two semesters in each academic year.</p>
          <p><strong>M.A. English. (Aided)</strong> - The duration of the course shall be two academic years comprising four semesters with two semesters in each academic year.</p>
          <p><strong>M.Phil. (Self-finance)</strong> - The duration of the course shall be one academic year comprising two semesters.</p>
        </div>

        <div id="coursecontent" class="tab-panel">
          <h3>COURSE CONTENT</h3>
          <p>The course content table/image is shown below. (Admin can replace this image)</p>
          <div class="course-image-wrap">
            <img id="courseImage" src="/mnt/data/5e38e518-ebc7-4397-97f6-a6ebb55841b6.png" alt="Course Content" />
          </div>
          <p>Course Content</p>
        </div>

        <div id="certification" class="tab-panel">
          <h3>CERTIFICATION COURSE</h3>
          <p>Certificate course on Creative writing and public speaking</p>
        </div>

        <div id="futureplan" class="tab-panel">
          <h3>FUTURE PLAN</h3>
          <ul>
            <li>To apply for Minor/Major research project.</li>
            <li>To upgrade the Language Lab.</li>
            <li>To conduct Theatre Fest.</li>
            <li>To conduct free coaching classes to students appearing for SET/NET.</li>
            <li>To prepare ICT tools.</li>
            <li>To start ISSN journal.</li>
          </ul>
        </div>
      </div>
    </aside>

<section class="faculty-card card">
  <h2>Faculty Members</h2>
  <div class="faculty-wrap">

<?php
if ($faculty && mysqli_num_rows($faculty) > 0) {

    echo "<table class='faculty-table'>
        <thead>
            <tr>
                <th>Sl.No.</th>
                <th>Name of the Staff</th>
                <th>Designation</th>
                <th>Profile</th>
            </tr>
        </thead>
        <tbody>";

    $i = 1;
    while ($row = mysqli_fetch_assoc($faculty)) {

        $pdf = trim($row['profile_pdf']);
        $link = ($pdf != "") ? "./" . $pdf : "#";
        $text = ($pdf != "") ? "View" : "N/A";

        echo "<tr>
            <td>$i</td>
            <td>".htmlspecialchars($row['faculty_name'])."</td>
            <td>".htmlspecialchars($row['designation'])."</td>
            <td><a href='$link' target='_blank'>$text</a></td>
        </tr>";

        $i++;
    }

    echo "</tbody></table>";

} else {
    echo "<p>No faculty members found.</p>";
}
?>

  </div>
</section>

  </main>

  <footer class="site-footer">
    <div class="wrap">
      <p>© 2025 Powered by Sourashtra College</p>
    </div>
  </footer>

  <script src="./JS/dept-eng.js"></script>
</body>
</html>