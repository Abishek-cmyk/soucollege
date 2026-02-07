<?php
// Include database connection (Line 2)
include_once 'db.php';

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch faculty members (Data is fetched here once)
$faculty = mysqli_query($conn, "SELECT * FROM `tamil_faculty` ORDER BY id ASC");
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
  <title>Department of Tamil - Viewer</title>
  <link rel="stylesheet" href="./CSS/dept-eng.css" />
   <link rel="stylesheet" href="./CSS/dept-tam.css" />
</head>
<body>
  <header class="site-header">
    <div class="wrap">
      <h1 class="title">Department of Tamil</h1>
      <p class="subtitle">History of Tamil Department</p>
    </div>
  </header>

  <main class="wrap main-grid">
    <section class="intro card">
      <p>Sourashtra College was started in 1967. The sanctioned post was four. At that time, the Part I Tamil Department was established, and Dr. Ramakodi was the head of the department. Under his leadership, there were three assistant professors:</p>
<p>1. Prof. V. Sonai M.A.<br>
2. Dr. P.M. Santhamoorthy M.A., Ph.D.<br>
3. Prof. D.R. Subramanian M.A.</p>

<p>When Prof. Dr. K.V. Ramakodi retired and Prof. V. Sonai retired, Dr. P.M. Santhamoorthy became the head of the department. Under his leadership, the assistant professors were:</p>
<p>1. Prof. D.R. Subramanian M.A., M.Phil.<br>
2. Dr. R. Chitra M.A., M.Phil., Ph.D. (replacing Prof. V. Sonai)<br>
3. Prof. N.K. Nirmala M.A., M.Phil. (replacing Dr. K.V. Ramakodi)</p>

<p>When Dr. P.M. Santhamoorthy retired, Thiru Prof. D.R. Subramanian became the head of the department. Under his leadership, the assistant professors were:</p>
<p>1. Dr. R. Chitra M.A., M.Phil., Ph.D.<br>
2. Prof. N.K. Nirmala M.A., M.Phil.<br>
3. Prof. R.R. Kubendran M.A., M.Phil., B.Ed. (Appointed on 16.02.2000, replacing Dr. P.M. Santhamoorthy)</p>

<p>When Thiru Prof. D.R. Subramanian retired, Mrs. Dr. R. Chitra became the head of the department. Under her leadership, the assistant professors were:</p>
<p>1. Prof. N.K. Nirmala M.A., M.Phil.<br>
2. Prof. R.R. Kubendran M.A., M.Phil., B.Ed.<br>
3. Dr. J.S. Urmila M.A., M.Phil., Ph.D. (S/F - Management Salary)<br>
4. Dr. K.R. Lakshimi M.A., M.Phil., Ph.D. (Appointed on 15.10.2007, replacing Prof. D.R. Subramanian)</p>

<p>Prof. N.K. Nirmala retired on 17.03.2007. After her retirement, Dr. K. Muthamil Selvi M.A., M.Phil., Ph.D., was appointed on 14.08.2013 (replacing Prof. N.K. Nirmala).</p>

<p>Dr. J.S. Urmila was appointed as the Principal of Sourashtra College for Women on 22.07.2015. In her place, Dr. S.R. Devika was appointed on 22.07.2015.</p>

<p>So now, under the leadership of Mrs. Dr. R. Chitra, the Tamil department is functioning with four faculties:</p>
<p>1. Prof. R.R. Kubendran M.A., M.Phil., B.Ed., M.A. (Eng), D.G.T.<br>
2. Dr. K.R. Lakshimi M.A., M.Phil., Ph.D.<br>
3. Dr. K. Muthamil Selvi M.A., M.Phil., Ph.D.<br>
4. Dr. S.R. Devika M.A., M.Phil., Ph.D., P.G.D.C.A. (S/F - Management Salary)</p>

<p>Mrs. Dr. R. Chitra retired on 31.05.2019. After her retirement, the department is now functioning under the headship of Mr. Prof. R.R. Kubendran with the following faculties:</p>
<p>1. Dr. K.R. Lakshimi M.A., M.Phil., Ph.D.<br>
2. Dr. K. Muthamil Selvi M.A., M.Phil., Ph.D.<br>
3. Dr. J.S. Urmila M.A., M.Phil., Ph.D. (Appointed on 04.11.2022)</p>
    </section>

    <aside class="overview-card card">
      <h2>OVERVIEW</h2>
      <div class="sidebar">
        <button class="tab-btn active" data-target="vision">OBJECTIVES</button>
        <button class="tab-btn" data-target="eligibility">SPECIAL FEATURES</button>
        <button class="tab-btn" data-target="duration">FUTURE PLANNING</button>
        <button class="tab-btn" data-target="coursecontent">COURSE CONTENT</button>
        <button class="tab-btn" data-target="certification">CERTIFICATION COURSE</button>
        <button class="tab-btn" data-target="futureplan">ACTIVITIES</button>
      </div>
      <div class="tab-content">
       <div id="vision" class="tab-panel active">
        <h3>OBJECTIVES</h3>
        <ul>
          <li>To develop the writing skills, oratory skills &amp; service mind through teaching Tamil.</li>
          <li>To preserve the Tamil Culture and Arts.</li>
        </ul>
      </div>


        <div id="eligibility" class="tab-panel">
  <h3>SPECIAL FEATURES</h3>
  <ul>
    <li>The department conducts periodically <strong>Vasakar Vattam</strong>.</li>
    <li>We are developing the students’ mentality to serve the society by helping the aged people, visually challenged and transgender people.</li>
  </ul>
</div>


        <div id="duration" class="tab-panel">
  <h3>FUTURE PLANNING</h3>
  <ul>
    <li>We have planned to initiate P.G. Tamil as a major subject with computer application.</li>
  </ul>
</div>

        <div id="coursecontent" class="tab-panel">
          <h3>COURSE CONTENT</h3>
          <p>The course content table/image is shown below. (Admin can replace this image)</p>
          <div class="course-image-wrap">
            <img id="courseImage" src="/mnt/data/5e38e518-ebc7-4397-97f6-a6ebb55841b6.png" alt="Course Content" />
          </div>
          <p>Course Content-க்கான டேபிள் அல்லது இமேஜ் இங்கே வரும்.</p>
        </div>

        <div id="certification" class="tab-panel">
          <h3>CERTIFICATION COURSE</h3>
          <p>Certificate course on இதழியல்</p>
        </div>

<div id="futureplan" class="tab-panel">
  <h3>ACTIVITIES</h3>

<?php
include 'db.php';

$q = mysqli_query($conn, "SELECT * FROM tamil_activities ORDER BY created_at DESC");

if ($q && mysqli_num_rows($q) > 0) {

    echo "<table class='activities-table'>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Activity Title</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody>";

    $i = 1;
    while ($row = mysqli_fetch_assoc($q)) {

        $title = htmlspecialchars($row['title']);
        $pdf   = $row['activity_pdf'];

        echo "<tr>
            <td>$i</td>
            <td>$title</td>
            <td><a href='$pdf' target='_blank'>View Activity PDF</a></td>
        </tr>";

        $i++;
    }

    echo "</tbody></table>";

} else {
    echo "<p>No activities uploaded.</p>";
}
?>

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
        $link = ($pdf != "") ? $pdf : "#";
        $text = ($pdf != "") ? "View" : "N/A";

        echo "<tr>
            <td>$i</td>
            <td>" . htmlspecialchars($row['faculty_name']) . "</td>
            <td>" . htmlspecialchars($row['designation']) . "</td>
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