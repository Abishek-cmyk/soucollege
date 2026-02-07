document.addEventListener('DOMContentLoaded', () => {
    // --- Existing Tab Logic ---
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');

            // Deactivate all
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Activate current
            button.classList.add('active');
            document.getElementById(targetId).classList.add('active');

            // --- NEW: Course Content Specific Logic ---
            if (targetId === 'coursecontent') {
                // Initialize/load the dynamic course content view
                initializeCourseContentView();
            }
        });
    });

    // --- NEW: Dynamic Course Content Logic ---
    const courseContentPanel = document.getElementById('coursecontent');
    let contentInitialized = false;

    // Defines the possible semesters for each department
    const semesterOptions = {
        'B.A. English': [1, 2, 3, 4, 5, 6],
        'M.A. English': [1, 2, 3, 4],
        'M.Phil.': [1, 2]
    };

    // Function to create the dropdowns and table structure
    const createCourseContentUI = () => {
        courseContentPanel.innerHTML = `
            <h3>COURSE CONTENT</h3>
            <div style="margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-radius: 5px;">
                <label for="deptSelect" style="font-weight: bold;">Department:</label>
                <select id="deptSelect" required style="padding: 8px; margin-right: 20px; border: 1px solid #ccc;">
                    <option value="">-- Select Department --</option>
                    <option value="B.A. English">B.A. English</option>
                    <option value="M.A. English">M.A. English</option>
                    <option value="M.Phil.">M.Phil.</option>
                </select>
                
                <label for="semSelect" style="font-weight: bold;">Semester:</label>
                <select id="semSelect" required disabled style="padding: 8px; border: 1px solid #ccc;">
                    <option value="">-- Select Semester --</option>
                </select>
            </div>
            
            <div class="course-table-container">
                <table class="course-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #e0e0e0;">
                            <th style="border: 1px solid #ccc; padding: 10px;">S.No.</th>
                            <th style="border: 1px solid #ccc; padding: 10px;">Subject Title</th>
                            <th style="border: 1px solid #ccc; padding: 10px;">Subject Code</th>
                        </tr>
                    </thead>
                    <tbody id="viewerCourseTableBody">
                        <tr><td colspan="3" style="text-align: center; padding: 10px;">Please select a Department and Semester to view the courses.</td></tr>
                    </tbody>
                </table>
            </div>
        `;
        contentInitialized = true;
    };

    // Function to load and display the courses
    const loadAndDisplayCourses = () => {
        const deptSelect = document.getElementById('deptSelect');
        const semSelect = document.getElementById('semSelect');
        const viewerCourseTableBody = document.getElementById('viewerCourseTableBody');
        
        const dept = deptSelect.value;
        const sem = semSelect.value;
        
        viewerCourseTableBody.innerHTML = ''; // Clear table body

        if (!dept || !sem) {
            viewerCourseTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 10px;">Please select a Department and Semester to view the courses.</td></tr>';
            return;
        }

        const key = `courses_${dept}_Sem${sem}`;
        
        try {
            const data = localStorage.getItem(key);
            const courses = data ? JSON.parse(data) : [];

            if (courses.length === 0) {
                viewerCourseTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 10px;">No course content has been uploaded for this selection.</td></tr>';
                return;
            }

            courses.forEach((course, index) => {
                const row = viewerCourseTableBody.insertRow();
                row.innerHTML = `
                    <td style="border: 1px solid #ddd; padding: 8px;">${index + 1}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${course.title}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${course.code}</td>
                `;
            });
        } catch (e) {
            console.error("Error displaying courses:", e);
            viewerCourseTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 10px;">Error loading course data.</td></tr>';
        }
    };

    // Main initializer for the Course Content tab
    const initializeCourseContentView = () => {
        if (!contentInitialized) {
            createCourseContentUI();
        }

        const deptSelect = document.getElementById('deptSelect');
        const semSelect = document.getElementById('semSelect');

        // Populates the semester dropdown
        const populateViewerSemesters = (dept) => {
            semSelect.innerHTML = '<option value="">-- Select Semester --</option>';
            semSelect.disabled = true;

            if (dept && semesterOptions[dept]) {
                semesterOptions[dept].forEach(sem => {
                    const option = document.createElement('option');
                    option.value = sem;
                    option.textContent = `Semester ${sem}`;
                    semSelect.appendChild(option);
                });
                semSelect.disabled = false;
            }
        };

        // Event Listeners for dropdowns
        deptSelect.addEventListener('change', (e) => {
            populateViewerSemesters(e.target.value);
            semSelect.value = ""; 
            loadAndDisplayCourses();
        });

        semSelect.addEventListener('change', loadAndDisplayCourses);

        // Initial check in case a value was set/cached
        populateViewerSemesters(deptSelect.value);
        loadAndDisplayCourses(); 
    };
    
    // --- Keep your existing faculty table logic here if any ---
    // ...
});

document.addEventListener('DOMContentLoaded', () => {
    // --- Existing tab logic (keep this) ---
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');

            // Deactivate all
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Activate current
            button.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // --- NEW: Faculty Display Logic (Complete and Corrected) ---
const facultyTableBody = document.querySelector('#facultyTable tbody');
const FACULTY_STORAGE_KEY = 'facultyMembers';
// 🚨 IMPORTANT: Use the relative path to go up one level from the JS/ folder 
// to the root folder, and then down into the profiles/ folder.
const PROFILE_FOLDER = './profiles/'; 

const loadAndDisplayFaculty = () => {
    facultyTableBody.innerHTML = ''; // Clear table body

    let faculty = [];
    try {
        const data = localStorage.getItem(FACULTY_STORAGE_KEY);
        faculty = data ? JSON.parse(data) : [];
    } catch (e) {
        console.error("Error loading faculty data for viewer:", e);
        facultyTableBody.innerHTML = '<tr><td colspan="4">Error loading faculty data.</td></tr>';
        return;
    }

    if (faculty.length === 0) {
        facultyTableBody.innerHTML = '<tr><td colspan="4">No faculty members currently listed.</td></tr>';
        return;
    }

    faculty.forEach((member, index) => {
        const profileLinkValue = member.profileFile; // This holds the filename (e.g., 'Sivabalan.pdf')
        let profileLinkHTML = 'N/A';

        if (profileLinkValue) {
            
            // 1. Determine the URL: Use the relative path to find the PDF.
            // Based on your file structure (JS/dept-eng.js is one level down from profiles/),
            // '../profiles/' is the correct path.
            const fileURL = PROFILE_FOLDER + profileLinkValue;

            // 2. VIEW Link (Opens in new tab, allowing the browser's PDF viewer to run)
            const viewLink = `<a href="${fileURL}" target="_blank" class="profile-view-link">View Profile</a>`;
            
            // 3. DOWNLOAD Link (Uses the 'download' attribute to force saving the file)
            const downloadLink = `<a href="${fileURL}" download="${profileLinkValue}" class="profile-download-link">Download PDF</a>`;
            
            // Combine the links
            profileLinkHTML = `${viewLink} | ${downloadLink}`;
        }

        const row = facultyTableBody.insertRow();
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${member.name}</td>
            <td>${member.designation}</td>
            <td>${profileLinkHTML}</td>
        `;
    });
};

// Initialize the faculty table display when the page loads
loadAndDisplayFaculty();
});