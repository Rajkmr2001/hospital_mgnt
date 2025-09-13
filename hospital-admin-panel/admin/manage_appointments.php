<?php
include('php/auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Appointments - Maa Kalawati Hospital</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  <style>
    /*==========================================
    GLOBAL STYLES & RESET - Improved for Readability
    ==========================================*/
    
    /* Universal reset - PERFORMANCE: Using border-box for consistent sizing */
    *, *::before, *::after {
      box-sizing: border-box;
    }

    /* Base html styles - BEST PRACTICE: Modern baseline */
    html {
      font-size: 16px;
      line-height: 1.15;
      scroll-behavior: smooth;
      -webkit-text-size-adjust: 100%;
      -moz-text-size-adjust: 100%;
      text-size-adjust: 100%;
    }

    /* Body base styles - READABILITY: Organized properties, fallbacks */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background-color: var(--color-bg, #f8fafc);
      color: var(--color-text, #222222);
      line-height: 1.6;
      
      /* PERFORMANCE: Font rendering optimization */
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
    }

    /*==========================================
    CSS CUSTOM PROPERTIES - MAINTAINABILITY: Centralized theme
    ==========================================*/
    :root {
      /* Color System - CONSISTENCY: Semantic naming */
      --color-primary: #2563eb;
      --color-primary-light: #60a5fa;
      --color-primary-dark: #1d4ed8;
      --color-accent: #10b981;
      --color-success: #10b981;
      --color-warning: #f59e0b;
      --color-error: #ef4444;
      --color-info: #3b82f6;
      --color-bg: #f8fafc;
      --color-card: #ffffff;
      --color-navbar: #ffffff;
      --color-border: #e2e8f0;
      --color-text: #222222;
      --color-text-muted: #64748b;
      --color-text-light: #94a3b8;

      /* Shadow System - PERFORMANCE: Hardware acceleration ready */
      --shadow-navbar: 0 2px 12px rgba(37, 99, 235, 0.07);
      --shadow-card: 0 4px 24px rgba(37, 99, 235, 0.10);
      --shadow-modal: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

      /* Border Radius System */
      --radius-sm: 6px;
      --radius: 12px;
      --radius-lg: 18px;

      /* Spacing System - MAINTAINABILITY: Consistent scale */
      --space-xs: 4px;
      --space-sm: 8px;
      --space-md: 16px;
      --space-lg: 24px;
      --space-xl: 32px;
      --space-2xl: 48px;

      /* Typography System */
      --font-family-base: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      --font-weight-normal: 400;
      --font-weight-medium: 500;
      --font-weight-semibold: 600;
      --font-weight-bold: 700;

      /* Z-index System */
      --z-navbar: 50;
      --z-dropdown: 100;
      --z-modal: 1000;

      /* Transition System - PERFORMANCE: Optimized durations */
      --transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
      --transition-normal: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      --transition-slow: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Dark Mode Support - BEST PRACTICE: System preference */
    @media (prefers-color-scheme: dark) {
      :root {
        --color-bg: #0f172a;
        --color-card: #1e293b;
        --color-navbar: #1e293b;
        --color-border: #334155;
        --color-text: #f1f5f9;
        --color-text-muted: #cbd5e1;
      }
      
      body {
        background-color: var(--color-bg);
        color: var(--color-text);
      }
    }

    /* High Contrast Mode - ACCESSIBILITY: Enhanced visibility */
    @media (prefers-contrast: high) {
      :root {
        --color-text: #000000;
        --color-bg: #ffffff;
        --color-card: #ffffff;
        --color-border: #000000;
      }
    }

    /* Reduced Motion - ACCESSIBILITY: Respect user preferences */
    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        transition-delay: 0ms !important;
      }
      
      html {
        scroll-behavior: auto;
      }
    }

    /* Focus Management - ACCESSIBILITY: Keyboard navigation */
    *:focus-visible {
      outline: 2px solid var(--color-primary);
      outline-offset: 2px;
      border-radius: var(--radius-sm);
    }

    /* Focus for non-visible elements */
    *:focus:not(:focus-visible) {
      outline: none;
    }

    /*==========================================
    NAVBAR COMPONENT - CONTINUED FROM ORIGINAL
    ==========================================*/
    .navbar {
      width: 100%;
      background-color: var(--color-navbar);
      box-shadow: var(--shadow-navbar);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 var(--space-xl);
      height: 64px;
      position: sticky;
      top: 0;
      z-index: var(--z-navbar);
      border-bottom: 1px solid var(--color-border);
      
      /* PERFORMANCE: GPU acceleration for smooth scrolling */
      will-change: transform;
      transform: translateZ(0);
      backface-visibility: hidden;
    }

    /*==========================================
    UTILITY CLASSES - MAINTAINABILITY: Reusable patterns
    ==========================================*/
    
    /* Screen Reader Only - ACCESSIBILITY: Hidden from sight, not screen readers */
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    /* Visually Hidden - ACCESSIBILITY: For interactive elements */
    .visually-hidden {
      border: 0;
      clip: rect(0 0 0 0);
      height: auto;
      margin: 0;
      overflow: hidden;
      padding: 0;
      position: absolute;
      width: 1px;
      white-space: nowrap;
    }

    /* Container Utility */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 var(--space-lg);
    }

    /* Text Truncation - EDGE CASE: Long content handling */
    .text-truncate {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    /* Loading Skeleton - UX: Better perceived performance */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
      border-radius: var(--radius-sm);
    }

    @keyframes loading {
      0% { 
        background-position: 200% 0; 
      }
      100% { 
        background-position: -200% 0; 
      }
    }

    /*==========================================
    RESPONSIVE DESIGN - MOBILE FIRST
    ==========================================*/
    
    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) { 
      :root {
        --space-xl: 40px;
      }
    }

    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) { 
      .navbar {
        padding: 0 var(--space-2xl);
      }
      
      html {
        font-size: 17px;
      }
    }

    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) { 
      html {
        font-size: 18px;
      }
    }

    /* Extra large devices (large desktops, 1200px and up) */
    @media (min-width: 1200px) { 
      .container {
        max-width: 1400px;
      }
    }

    /*==========================================
    PRINT STYLES - BEST PRACTICE: Document friendly
    ==========================================*/
    @media print {
      .navbar,
      .modal,
      button:not([type="submit"]) {
        display: none !important;
      }
      
      body {
        font-size: 12pt;
        line-height: 1.4;
        color: #000;
        background: #fff;
      }
      
      table {
        width: 100%;
        border-collapse: collapse;
        page-break-inside: auto;
      }
      
      th, td {
        border: 1px solid #000;
        padding: 8px;
        page-break-inside: avoid;
      }
      
      tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }
    }

    /*==========================================
    ERROR HANDLING & FALLBACKS
    ==========================================*/
    
    /* Fallback for browsers without CSS Grid support */
    @supports not (display: grid) {
      .grid {
        display: block;
      }
      
      .grid-item {
        float: left;
        width: 100%;
        margin-bottom: var(--space-sm);
      }
      
      .grid-item:nth-child(2n) {
        clear: both;
      }
    }

    /* Fallback for custom properties (older browsers) */
    .navbar {
      background-color: #ffffff; /* Fallback */
      box-shadow: 0 2px 12px rgba(37, 99, 235, 0.07); /* Fallback */
    }

    /* Edge case: Very small screens */
    @media (max-width: 320px) {
      .navbar {
        padding: 0 var(--space-sm);
        font-size: 14px;
      }
    }

    /* Edge case: Landscape orientation on mobile */
    @media (max-height: 500px) and (orientation: landscape) {
      .navbar {
        height: 50px;
      }
      
      html {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo"><i class="ri-hospital-line"></i> <span>Maa Kalawati Admin</span></div>
    <button class="hamburger" id="hamburger" aria-label="Open navigation">☰</button>
    <nav id="mainNav">
      <a href="dashboard.php">Dashboard</a>
      <a href="manage_patients.php">Patients</a>
      <a href="manage_appointments.php" class="active">Appointments</a>
      <a href="user_visits.php">Visits</a>
      <a href="manage_feedback.php">Feedback</a>
      <a href="manage_messages.php">Messages</a>
      <a href="manage_registered_persons.php">REG_PERSON</a>
    </nav>
    <a href="../../index.html" class="back-btn"><i class="ri-arrow-left-line"></i> Back to Home</a>
    <div class="profile" id="profileMenu">
      <div class="avatar"><i class="ri-user-3-line"></i></div>
      <span>Admin</span>
      <div class="dropdown">
        <a href="#">Profile</a>
        <a href="#">Settings</a>
        <a href="php/logout.php">Logout</a>
      </div>
    </div>
  </header>

  <section class="welcome-banner">
    <div>
      <div class="text">Manage Appointments</div>
      <div class="desc">View and manage all appointment records from patient_data2. Edit or delete patient submissions.</div>
    </div>
    <div class="banner-img"><i class="ri-calendar-line"></i></div>
  </section>

  <section class="appointments-section">
    <div class="appointments-header">
      <h2>Appointment Records</h2>
    </div>
    <div class="appointments-table-wrapper">
      <table class="appointments-table" id="appointmentsTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Contact</th>
            <th>Submission Time</th>
            <th>Submission Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="appointmentsTbody">
          <!-- Data will be loaded here -->
        </tbody>
      </table>
    </div>
  </section>

  <!-- Custom Alert Container -->
  <div id="customAlertContainer"></div>

  <!-- Edit Appointment Modal -->
  <div id="appointmentModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="modalTitle">Edit Appointment</h2>
      <form id="appointmentForm">
        <input type="hidden" id="appointmentId" name="patient_id">
        
        <div class="form-group">
          <label for="name">Name *</label>
          <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
          <label for="age">Age *</label>
          <input type="number" id="age" name="age" min="1" max="150" required>
        </div>
        
        <div class="form-group">
          <label for="gender">Gender *</label>
          <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="contact">Contact Number *</label>
          <input type="text" id="contact" name="contact" required>
        </div>
        
        <div class="modal-buttons">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Record</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Custom Alert Function
    function showCustomAlert(message, type = 'info') {
      const container = document.getElementById('customAlertContainer');
      const alertDiv = document.createElement('div');
      alertDiv.className = `custom-alert ${type}`;
      alertDiv.innerHTML = `
        <span>${message}</span>
        <span class="close-alert" onclick="this.parentElement.remove()">&times;</span>
      `;
      
      container.appendChild(alertDiv);
      
      // Show the alert
      setTimeout(() => {
        alertDiv.classList.add('show');
      }, 100);
      
      // Auto remove after 5 seconds
      setTimeout(() => {
        if (alertDiv.parentElement) {
          alertDiv.classList.remove('show');
          setTimeout(() => {
            if (alertDiv.parentElement) {
              alertDiv.remove();
            }
          }, 300);
        }
      }, 5000);
    }

    // Navbar hamburger menu logic
    const hamburger = document.getElementById('hamburger');
    const mainNav = document.getElementById('mainNav');
    if (hamburger && mainNav) {
      hamburger.addEventListener('click', function(e) {
        e.stopPropagation();
        mainNav.classList.toggle('open');
      });
      document.addEventListener('click', function(e) {
        if (window.innerWidth <= 900 && mainNav.classList.contains('open') && !mainNav.contains(e.target) && e.target !== hamburger) {
          mainNav.classList.remove('open');
        }
      });
      window.addEventListener('resize', function() {
        if (window.innerWidth > 900) mainNav.classList.remove('open');
      });
    }

    // Profile dropdown logic
    const profileMenu = document.getElementById('profileMenu');
    if (profileMenu) {
      profileMenu.addEventListener('click', function(e) { e.stopPropagation(); this.classList.toggle('open'); });
      document.addEventListener('click', function() { profileMenu.classList.remove('open'); });
    }

    // Modal functions
    function openEditModal(appointmentData) {
      document.getElementById('modalTitle').textContent = 'Edit Appointment Record';
      document.getElementById('appointmentId').value = appointmentData.id;
      document.getElementById('name').value = appointmentData.name || '';
      document.getElementById('age').value = appointmentData.age || '';
      document.getElementById('gender').value = appointmentData.gender || '';
      document.getElementById('contact').value = appointmentData.contact || '';
      document.getElementById('appointmentModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('appointmentModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('appointmentModal');
      if (event.target === modal) {
        closeModal();
      }
    }

    // Form submission
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const appointmentId = formData.get('patient_id');
      
      fetch('php/update_patient_data2.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          closeModal();
          loadAppointments();
          showCustomAlert('Appointment record updated successfully!', 'success');
        } else {
          showCustomAlert('Error: ' + (data.message || 'Unknown error'), 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showCustomAlert('An error occurred. Please try again.', 'error');
      });
    });

    // Load appointments
    function loadAppointments() {
      fetch('php/get_patient_data2.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          const tbody = document.getElementById('appointmentsTbody');
          tbody.innerHTML = '';
          
          if (Array.isArray(data) && data.length > 0) {
            data.forEach(appointment => {
              const tr = document.createElement('tr');
              tr.innerHTML = `
                <td>${appointment.id}</td>
                <td>${appointment.name || 'N/A'}</td>
                <td>${appointment.age || 'N/A'}</td>
                <td>${appointment.gender || 'N/A'}</td>
                <td>${appointment.contact || 'N/A'}</td>
                <td>${appointment.submission_time || 'N/A'}</td>
                <td>${appointment.submission_date || 'N/A'}</td>
                <td>
                  <div class="action-buttons">
                    <button class="edit-btn" onclick="openEditModal(${JSON.stringify(appointment).replace(/"/g, '"')})">Edit</button>
                    <button class="delete-btn" onclick="deleteAppointment(${appointment.id})">Delete</button>
                  </div>
                </td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: #888; padding: 40px;">No appointment records found</td></tr>';
          }
        })
        .catch(error => {
          console.error('Error loading appointments:', error);
          document.getElementById('appointmentsTbody').innerHTML = '<tr><td colspan="8" style="text-align: center; color: #888; padding: 40px;">Error loading appointments</td></tr>';
          showCustomAlert('Error loading appointments. Please refresh the page.', 'error');
        });
    }

    // Delete appointment
    function deleteAppointment(appointmentId) {
      if (confirm('Are you sure you want to delete this appointment record? This action cannot be undone.')) {
        fetch('php/delete_patient_data2.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `patient_id=${appointmentId}`
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            loadAppointments();
            showCustomAlert('Appointment record deleted successfully!', 'success');
          } else {
            showCustomAlert('Error: ' + (data.message || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showCustomAlert('An error occurred. Please try again.', 'error');
        });
      }
    }

    // Load appointments on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadAppointments();
    });
  </script>
</body>
</html>