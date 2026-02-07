document.addEventListener('DOMContentLoaded', () => {
    const facultyTableBodyAdmin = document.getElementById('facultyTableBodyAdmin');
    const addFacultyForm = document.getElementById('addFacultyForm');
    const facultyIndexInput = document.getElementById('facultyIndex');
    const staffNameInput = document.getElementById('staffName');
    const staffDesignationInput = document.getElementById('staffDesignation');
    const profileFileInput = document.getElementById('profileFile'); // NEW: File input
    const currentFileNameSpan = document.getElementById('currentFileName'); // NEW: For displaying current file
    const facultySubmitBtn = document.getElementById('facultySubmitBtn');
    
    const STORAGE_KEY = 'facultyMembers';

    // --- Helper Functions ---

    // Load faculty data from localStorage
    const loadFaculty = () => {
        try {
            const data = localStorage.getItem(STORAGE_KEY);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error("Error loading faculty data:", e);
            return [];
        }
    };

    // Save faculty data to localStorage
    const saveFaculty = (faculty) => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(faculty));
    };

    // Render the faculty table in the admin panel
    const renderFacultyTableAdmin = () => {
        const faculty = loadFaculty();
        facultyTableBodyAdmin.innerHTML = ''; // Clear existing rows

        if (faculty.length === 0) {
            facultyTableBodyAdmin.innerHTML = '<tr><td colspan="5">No faculty members added.</td></tr>';
            return;
        }

        faculty.forEach((member, index) => {
            const fileNameDisplay = member.profileFile ? member.profileFile : 'N/A';
            const profileLink = member.profileFile ? `<a href="/profiles/${member.profileFile}" target="_blank">Download</a>` : 'N/A';

            const row = facultyTableBodyAdmin.insertRow();
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${member.name}</td>
                <td>${member.designation}</td>
                <td>${fileNameDisplay} (${profileLink})</td>
                <td>
                    <button class="edit-btn" data-index="${index}">Edit</button>
                    <button class="delete-btn" data-index="${index}">Delete</button>
                </td>
            `;
        });
    };
    
    // Resets the file display and form
    const resetFacultyForm = () => {
        addFacultyForm.reset(); 
        facultyIndexInput.value = -1; 
        facultySubmitBtn.textContent = 'Add Faculty'; 
        currentFileNameSpan.textContent = 'None';
    };

    // --- Event Listeners ---

    // Handle Add/Update form submission
    addFacultyForm.addEventListener('submit', (e) => {
        e.preventDefault();

        let faculty = loadFaculty();
        const index = parseInt(facultyIndexInput.value);

        // Determine the file name to save
        let fileName;
        if (profileFileInput.files.length > 0) {
            // New file selected, use its name
            fileName = profileFileInput.files[0].name;
            // NOTE: In a real system, the file would be uploaded here.
            alert(`NOTE: Please manually upload the file "${fileName}" to the /profiles/ folder on the server.`);
        } else if (index !== -1) {
            // No new file selected during edit, keep the existing file name
            fileName = faculty[index].profileFile;
        } else {
            // New entry, no file selected
            fileName = '';
        }

        const newMember = {
            name: staffNameInput.value.trim(),
            designation: staffDesignationInput.value.trim(),
            profileFile: fileName // Store the file name
        };

        if (index === -1) {
            // Add (Create)
            faculty.push(newMember);
            alert('Faculty Member Added Successfully!');
        } else {
            // Update
            faculty[index] = newMember;
            alert('Faculty Member Updated Successfully!');
        }

        saveFaculty(faculty);
        renderFacultyTableAdmin();
        resetFacultyForm();
    });

    // Handle Edit and Delete buttons on the table
    facultyTableBodyAdmin.addEventListener('click', (e) => {
        const index = e.target.getAttribute('data-index');
        if (index === null) return;

        let faculty = loadFaculty();
        const facultyIndex = parseInt(index);

        if (e.target.classList.contains('edit-btn')) {
            // Edit (Update) action: Load data into form
            const memberToEdit = faculty[facultyIndex];
            staffNameInput.value = memberToEdit.name;
            staffDesignationInput.value = memberToEdit.designation;
            
            // Set index and update button text
            facultyIndexInput.value = facultyIndex; 
            facultySubmitBtn.textContent = 'Update Faculty'; 
            
            // Display current file name, but keep file input blank for a new upload
            currentFileNameSpan.textContent = memberToEdit.profileFile || 'None';
            profileFileInput.value = ''; // Clear file input for a fresh selection

            window.scrollTo({ top: document.getElementById('addFacultyForm').offsetTop, behavior: 'smooth' });
        } else if (e.target.classList.contains('delete-btn')) {
            // Delete action
            if (confirm('Are you sure you want to delete this faculty member?')) {
                faculty.splice(facultyIndex, 1);
                saveFaculty(faculty);
                renderFacultyTableAdmin();
                alert('Faculty Member Deleted Successfully!');
            }
        }
    });

    // Initial setup
    renderFacultyTableAdmin();
});