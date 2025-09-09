<?php
include('php/auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Visits Analytics - Maa Kalawati Hospital</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    html, body { margin: 0; padding: 0; font-family: 'Poppins', Arial, sans-serif; background: #f8fafc; color: #222; min-height: 100vh; width: 100vw; box-sizing: border-box; overflow-x: hidden; }
    *, *::before, *::after { box-sizing: inherit; }
    :root { --primary: #2563eb; --primary-light: #60a5fa; --accent: #10b981; --bg: #f8fafc; --card-bg: #fff; --navbar-bg: #fff; --navbar-shadow: 0 2px 12px rgba(37,99,235,0.07); --text: #222; --muted: #64748b; --radius: 18px; --shadow: 0 4px 24px rgba(37,99,235,0.10); }
    
    .navbar { width: 100%; background: var(--navbar-bg); box-shadow: var(--navbar-shadow); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; height: 64px; position: sticky; top: 0; z-index: 100; }
    .navbar .logo { display: flex; align-items: center; font-size: 1.5rem; font-weight: 700; color: var(--primary); gap: 10px; }
    .navbar nav { display: flex; gap: 28px; }
    .navbar nav a { text-decoration: none; color: var(--muted); font-weight: 600; font-size: 1.08rem; padding: 8px 0; border-bottom: 2px solid transparent; transition: color 0.2s, border-color 0.2s; }
    .navbar nav a.active, .navbar nav a:hover { color: var(--primary); border-bottom: 2px solid var(--primary); }
    .navbar .back-btn { display: flex; align-items: center; background: #e0e7ef; color: var(--primary); border: none; border-radius: 8px; font-weight: 600; padding: 8px 16px; cursor: pointer; text-decoration: none; transition: background 0.2s; }
    .navbar .profile { display: flex; align-items: center; gap: 12px; cursor: pointer; position: relative; }
    .navbar .profile .avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--accent)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    
    .main-content { display: grid; grid-template-columns: 300px 1fr; gap: 32px; padding: 32px; max-width: 1400px; margin: 0 auto; }
    .sidebar { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); padding: 24px; height: fit-content; }
    .sidebar h3 { margin: 0 0 20px 0; font-size: 1.4rem; color: var(--primary); }
    .calendar-input { width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
    .stats-container { margin-top: 24px; }
    .stat-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
    .stat-item:last-child { border-bottom: none; }
    .stat-label { font-weight: 600; color: var(--muted); }
    .stat-value { font-weight: 700; color: var(--primary); font-size: 1.2rem; }
    
    .content-area { display: flex; flex-direction: column; gap: 32px; }
    .chart-card, .table-card { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); padding: 24px; }
    .chart-card h3, .table-card h3 { margin: 0 0 20px 0; font-size: 1.4rem; color: var(--primary); }
    .chart-container { position: relative; height: 350px; }
    .table-wrapper { overflow-x: auto; }
    .visitor-table { width: 100%; border-collapse: collapse; }
    .visitor-table th, .visitor-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .visitor-table th { background-color: #f8fafc; font-weight: 600; }
    
    .loading-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; z-index: 10; border-radius: var(--radius); }
    .loader { border: 4px solid #f3f3f3; border-top: 4px solid var(--primary); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo"><i class="ri-hospital-line"></i> <span>Maa Kalawati Admin</span></div>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="manage_patients.php">Patients</a>
      <a href="user_visits.php" class="active">Visits</a>
      <a href="manage_feedback.php">Feedback</a>
      <a href="manage_messages.php">Messages</a>
      <a href="manage_registered_persons.php">REG_PERSON</a>
    </nav>
    <a href="../../index.html" class="back-btn"><i class="ri-arrow-left-line"></i> Back to Home</a>
    <div class="profile">
      <div class="avatar"><i class="ri-user-3-line"></i></div>
      <span>Admin</span>
    </div>
  </header>

  <main class="main-content">
    <aside class="sidebar">
      <h3><i class="ri-calendar-line"></i> Select Date</h3>
      <input id="calendar" type="text" class="calendar-input" placeholder="Select a date...">
      <div class="stats-container">
        <div class="stat-item">
          <span class="stat-label">Selected Date:</span>
          <span class="stat-value" id="selectedDate">Today</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Total Visits:</span>
          <span class="stat-value" id="totalVisits">0</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Unique Visitors:</span>
          <span class="stat-value" id="uniqueVisits">0</span>
        </div>
      </div>
    </aside>

    <div class="content-area">
      <div class="chart-card">
        <h3><i class="ri-line-chart-line"></i> 30-Day Visitor Trend</h3>
        <div class="chart-container">
          <canvas id="trendChart"></canvas>
          <div class="loading-overlay" id="trendChartLoader"><div class="loader"></div></div>
        </div>
      </div>
      <div class="table-card">
        <h3><i class="ri-global-line"></i> Visitor Log (Recent 100)</h3>
        <div class="table-wrapper">
          <table class="visitor-table">
            <thead>
              <tr>
                <th>IP Address</th>
                <th>First Visit</th>
                <th>Last Visit</th>
                <th>Total Visits</th>
              </tr>
            </thead>
            <tbody id="visitorLogBody">
            </tbody>
          </table>
          <div class="loading-overlay" id="visitorLogLoader"><div class="loader"></div></div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let trendChart;

      const calendar = flatpickr('#calendar', {
        defaultDate: 'today',
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        onChange: function(selectedDates, dateStr, instance) {
          fetchData(dateStr);
        }
      });

      async function fetchData(date) {
        showLoaders();
        try {
          const response = await fetch(`php/get_visitor_stats.php?date=${date}`);
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          const data = await response.json();
          updateUI(data);
        } catch (error) {
          console.error('Failed to fetch data:', error);
        } finally {
          hideLoaders();
        }
      }

      function updateUI(data) {
        // Update sidebar stats
        document.getElementById('selectedDate').textContent = new Date(data.selected_date + 'T00:00:00').toLocaleDateString();
        document.getElementById('totalVisits').textContent = data.stats.total_visits;
        document.getElementById('uniqueVisits').textContent = data.stats.unique_visits;

        // Update trend chart
        updateTrendChart(data.trend);

        // Update visitor log
        updateVisitorLog(data.log);
      }

      function updateTrendChart(trendData) {
        const ctx = document.getElementById('trendChart').getContext('2d');
        const labels = trendData.map(d => new Date(d.day + 'T00:00:00').toLocaleDateString());
        const counts = trendData.map(d => d.count);

        if (trendChart) {
          trendChart.destroy();
        }

        trendChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            datasets: [{
              label: 'Unique Visitors',
              data: counts,
              borderColor: 'rgba(37, 99, 235, 1)',
              backgroundColor: 'rgba(37, 99, 235, 0.1)',
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      }

      function updateVisitorLog(logData) {
        const tbody = document.getElementById('visitorLogBody');
        tbody.innerHTML = '';
        if (logData.length === 0) {
          tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No visitor data available.</td></tr>';
          return;
        }
        logData.forEach(log => {
          const row = `
            <tr>
              <td>${log.ip_address}</td>
              <td>${new Date(log.first_visit).toLocaleString()}</td>
              <td>${new Date(log.last_visit).toLocaleString()}</td>
              <td>${log.total_visits}</td>
            </tr>
          `;
          tbody.innerHTML += row;
        });
      }

      function showLoaders() {
        document.getElementById('trendChartLoader').style.display = 'flex';
        document.getElementById('visitorLogLoader').style.display = 'flex';
      }

      function hideLoaders() {
        document.getElementById('trendChartLoader').style.display = 'none';
        document.getElementById('visitorLogLoader').style.display = 'none';
      }

      // Initial data fetch
      fetchData(calendar.selectedDates[0] ? calendar.formatDate(calendar.selectedDates[0], 'Y-m-d') : new Date().toISOString().slice(0,10));
    });
  </script>
</body>
</html>
