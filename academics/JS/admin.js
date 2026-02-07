document.addEventListener('DOMContentLoaded', () => {

    const adminDeptSelect = document.getElementById('adminDeptSelect');
    const adminSemSelect = document.getElementById('adminSemSelect');
    const addCourseForm = document.getElementById('addCourseForm');
    const courseTableBody = document.getElementById('courseTableBody');
    const subjectTitleInput = document.getElementById('subjectTitle');
    const subjectCodeInput = document.getElementById('subjectCode');
    const courseIndexInput = document.getElementById('courseIndex');
    const submitBtn = document.getElementById('submitBtn');

    // Semester options
    const semesterOptions = {
        'B.A. English': [1, 2, 3, 4, 5, 6],
        'M.A. English': [1, 2, 3, 4],
        'M.Phil.': [1, 2]
    };

    // Generate localStorage key
    const getStorageKey = () => {
        const dept = adminDeptSelect.value;
        const sem = adminSemSelect.value;
        return dept && sem ? `courses_${dept}_Sem${sem}` : null;
    };

    // Load from localStorage
    const loadCourses = () => {
        const key = getStorageKey();
        if (!key) return [];
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error("Error loading:", e);
            return [];
        }
    };

    // Save to localStorage
    const saveCourses = (courses) => {
        const key = getStorageKey();
        if (key) {
            localStorage.setItem(key, JSON.stringify(courses));
        }
    };

    // Render Table
    const renderTable = () => {
        const courses = loadCourses();
        courseTableBody.innerHTML = '';

        if (courses.length === 0) {
            courseTableBody.innerHTML = `<tr><td colspan="4">No courses added for this selection.</td></tr>`;
            return;
        }

        courses.forEach((course, index) => {
            const row = courseTableBody.insertRow();
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${course.title}</td>
                <td>${course.code}</td>
                <td>
                    <button class="edit-btn" data-index="${index}">Edit</button>
                    <button class="delete-btn" data-index="${index}">Delete</button>
                </td>
            `;
        });
    };

    // Fill semesters dynamically
    const populateSemesters = (dept) => {
        adminSemSelect.innerHTML = '<option value="">-- Select Semester --</option>';
        adminSemSelect.disabled = true;

        if (dept && semesterOptions[dept]) {
            semesterOptions[dept].forEach(sem => {
                const option = document.createElement('option');
                option.value = sem;
                option.textContent = `Semester ${sem}`;
                adminSemSelect.appendChild(option);
            });
            adminSemSelect.disabled = false;
        }
    };

    // Department change
    adminDeptSelect.addEventListener('change', (e) => {
        populateSemesters(e.target.value);
        adminSemSelect.value = "";
        renderTable();
    });

    // Semester change
    adminSemSelect.addEventListener('change', () => {
        renderTable();
    });

    // Add / Update course
    addCourseForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const dept = adminDeptSelect.value;
        const sem = adminSemSelect.value;

        if (!dept || !sem) {
            alert('Please select Department & Semester first.');
            return;
        }

        const newCourse = {
            title: subjectTitleInput.value.trim(),
            code: subjectCodeInput.value.trim()
        };

        let courses = loadCourses();
        const index = parseInt(courseIndexInput.value);

        if (index === -1) {
            courses.push(newCourse);
            alert('Course Added Successfully!');
        } else {
            courses[index] = newCourse;
            alert('Course Updated Successfully!');
        }

        saveCourses(courses);
        renderTable();
        addCourseForm.reset();
        courseIndexInput.value = -1;
        submitBtn.textContent = 'Add Course';
    });

    // Edit / Delete button actions
    courseTableBody.addEventListener('click', (e) => {
        const index = e.target.getAttribute('data-index');
        if (index === null) return;

        let courses = loadCourses();
        const courseIndex = parseInt(index);

        if (e.target.classList.contains('edit-btn')) {
            const course = courses[courseIndex];
            subjectTitleInput.value = course.title;
            subjectCodeInput.value = course.code;
            courseIndexInput.value = courseIndex;
            submitBtn.textContent = 'Update Course';
            window.scrollTo({ top: 0, behavior: 'smooth' });

        } else if (e.target.classList.contains('delete-btn')) {
            if (confirm('Are you sure you want to delete this course?')) {
                courses.splice(courseIndex, 1);
                saveCourses(courses);
                renderTable();
                alert('Course Deleted Successfully!');
            }
        }
    });

    // Initial
    populateSemesters(adminDeptSelect.value);
    renderTable();
});
