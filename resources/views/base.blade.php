<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
</head>
<body>

<!-- Sidebar Overlay -->
@include('partials.sidebar')

<!-- Main Content -->
<div class="main-wrap" id="mainWrap">
    <!-- Header -->
    <header class="top-header">
        <button class="btn d-lg-none p-0 me-2" id="menuToggle" style="color:inherit;font-size:22px;">
            <i class="bi bi-list"></i>
        </button>

        <div class="search-input-wrap">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search or type command...">
            <span class="search-kbd">K</span>
        </div>

        <div class="ms-auto d-flex align-items-center gap-1">
            <button class="header-btn" id="themeToggle" title="Toggle Theme">
                <i class="bi bi-sun-fill" id="iconSun"></i>
                <i class="bi bi-moon-fill d-none" id="iconMoon"></i>
            </button>

            <button class="header-btn" title="Messages">
                <i class="bi bi-chat-dots"></i>
                <span class="dot"></span>
            </button>

            <button class="header-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="dot"></span>
            </button>

            <div class="dropdown">
                <div class="user-pill" data-bs-toggle="dropdown">
                    <div class="user-avatar-sm">
                        <i class="bi bi-person-fill" style="font-size:18px;"></i>
                    </div>
                    <div>
                        <div class="u-name">Administrator</div>
                        <div class="u-role">Super Admin</div>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size:12px;color:#94a3b8;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Dashboard Body -->
    <div class="dash-body">
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="letter-spacing:-0.5px;">Dashboard</h2>
            <p class="text-secondary small mb-0">Welcome back, Administrator. Here's what's happening today.</p>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card fade-up" style="animation-delay:.05s">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">
                            <i class="bi bi-people"></i>
                        </div>
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                            <i class="bi bi-arrow-up me-1"></i>11.01%
                        </span>
                    </div>
                    <div class="stat-value">3,782</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card fade-up" style="animation-delay:.1s">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:linear-gradient(135deg,#10b981,#059669);">
                            <i class="bi bi-book"></i>
                        </div>
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                            <i class="bi bi-arrow-up me-1"></i>5.24%
                        </span>
                    </div>
                    <div class="stat-value">128</div>
                    <div class="stat-label">Active Courses</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card fade-up" style="animation-delay:.15s">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                            <i class="bi bi-arrow-down me-1"></i>2.41%
                        </span>
                    </div>
                    <div class="stat-value">$52,340</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card fade-up" style="animation-delay:.2s">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                            <i class="bi bi-arrow-up me-1"></i>8.72%
                        </span>
                    </div>
                    <div class="stat-value">1,248</div>
                    <div class="stat-label">Enrollments</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Bar Chart -->
            <div class="col-xl-8">
                <div class="chart-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="chart-title">Monthly Enrollment</div>
                            <div class="chart-subtitle">Student enrollment per month in 2026</div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary rounded-3" title="More">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                    <div class="bar-chart-area" id="barChart"></div>
                </div>
            </div>

            <!-- Gauge -->
            <div class="col-xl-4">
                <div class="chart-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="chart-title">Monthly Target</div>
                            <div class="chart-subtitle">Target you've set for each month</div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary rounded-3" title="More">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                    <div class="gauge-wrap">
                        <svg width="200" height="120" viewBox="0 0 200 120">
                            <path d="M20 100 A80 80 0 0 1 180 100" fill="none" stroke="rgba(128,128,128,0.15)" stroke-width="16" stroke-linecap="round"/>
                            <path id="gaugePath" d="M20 100 A80 80 0 0 1 180 100" fill="none" stroke="url(#gaugeGrad)" stroke-width="16" stroke-linecap="round" stroke-dasharray="251.2" stroke-dashoffset="251.2"/>
                            <defs>
                                <linearGradient id="gaugeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#8b5cf6"/>
                                    <stop offset="100%" style="stop-color:#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="gauge-val">75.55%</div>
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1 mt-2">
                            <i class="bi bi-arrow-up me-1"></i>+10%
                        </span>
                        <div class="gauge-msg">
                            You earned $3,287 today, it's higher than last month.<br>Keep up your good work!
                        </div>
                        <div class="row g-2 mt-3 w-100">
                            <div class="col">
                                <div class="gauge-stat">
                                    <div class="gauge-stat-label">Target</div>
                                    <div class="gauge-stat-val">$20K</div>
                                    <div class="text-danger small fw-semibold">↓ 5%</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="gauge-stat">
                                    <div class="gauge-stat-label">Revenue</div>
                                    <div class="gauge-stat-val">$20K</div>
                                    <div class="text-success small fw-semibold">↑ 8%</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="gauge-stat">
                                    <div class="gauge-stat-label">Today</div>
                                    <div class="gauge-stat-val">$20K</div>
                                    <div class="text-success small fw-semibold">↑ 12%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Tables -->
        <div class="row g-4">
            <div class="col-xl-6">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center p-3 pb-0">
                        <div>
                            <div class="chart-title">Recent Students</div>
                            <div class="chart-subtitle">Latest enrolled students this month</div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary rounded-3">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0 align-middle">
                            <thead>
                                <tr class="border-bottom">
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Student</th>
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Course</th>
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Status</th>
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbl-avatar" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">AR</div>
                                            <span class="fw-medium">Ahmad Rizki</span>
                                        </div>
                                    </td>
                                    <td>Web Development</td>
                                    <td><span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1"><span class="me-1" style="font-size:8px;">●</span>Active</span></td>
                                    <td class="text-secondary small">Apr 25, 2026</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbl-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">SP</div>
                                            <span class="fw-medium">Siti Putri</span>
                                        </div>
                                    </td>
                                    <td>Data Science</td>
                                    <td><span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1"><span class="me-1" style="font-size:8px;">●</span>Active</span></td>
                                    <td class="text-secondary small">Apr 24, 2026</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbl-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);">BW</div>
                                            <span class="fw-medium">Budi Widodo</span>
                                        </div>
                                    </td>
                                    <td>UI/UX Design</td>
                                    <td><span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-1"><span class="me-1" style="font-size:8px;">●</span>Pending</span></td>
                                    <td class="text-secondary small">Apr 23, 2026</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbl-avatar" style="background:linear-gradient(135deg,#ec4899,#db2777);">DN</div>
                                            <span class="fw-medium">Dewi Nurhasanah</span>
                                        </div>
                                    </td>
                                    <td>Mobile Dev</td>
                                    <td><span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1"><span class="me-1" style="font-size:8px;">●</span>Active</span></td>
                                    <td class="text-secondary small">Apr 22, 2026</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbl-avatar" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">FH</div>
                                            <span class="fw-medium">Fajar Hidayat</span>
                                        </div>
                                    </td>
                                    <td>Cloud Computing</td>
                                    <td><span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1"><span class="me-1" style="font-size:8px;">●</span>Inactive</span></td>
                                    <td class="text-secondary small">Apr 21, 2026</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center p-3 pb-0">
                        <div>
                            <div class="chart-title">Top Courses</div>
                            <div class="chart-subtitle">Most popular courses this month</div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary rounded-3">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0 align-middle">
                            <thead>
                                <tr class="border-bottom">
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Course</th>
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Students</th>
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Rating</th>
                                    <th class="text-uppercase small fw-semibold text-secondary" style="font-size:11px;letter-spacing:.5px;">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">Web Development</td>
                                    <td>842</td>
                                    <td><span class="text-warning">★★★★★</span> <span class="text-secondary small ms-1">4.9</span></td>
                                    <td class="fw-semibold">$12,480</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Data Science</td>
                                    <td>654</td>
                                    <td><span class="text-warning">★★★★★</span> <span class="text-secondary small ms-1">4.8</span></td>
                                    <td class="fw-semibold">$9,820</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">UI/UX Design</td>
                                    <td>521</td>
                                    <td><span class="text-warning">★★★★</span><span class="text-secondary ms-1">★</span> <span class="text-secondary small ms-1">4.5</span></td>
                                    <td class="fw-semibold">$7,810</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Mobile Development</td>
                                    <td>438</td>
                                    <td><span class="text-warning">★★★★</span><span class="text-secondary ms-1">★</span> <span class="text-secondary small ms-1">4.3</span></td>
                                    <td class="fw-semibold">$6,570</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Cloud Computing</td>
                                    <td>312</td>
                                    <td><span class="text-warning">★★★★</span><span class="text-secondary ms-1">★</span> <span class="text-secondary small ms-1">4.2</span></td>
                                    <td class="fw-semibold">$4,680</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Theme Toggle
    const html = document.documentElement;
    const iconSun = document.getElementById('iconSun');
    const iconMoon = document.getElementById('iconMoon');

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        html.setAttribute('data-bs-theme', 'light');
        iconSun.classList.add('d-none');
        iconMoon.classList.remove('d-none');
    } else if (savedTheme === 'dark') {
        html.setAttribute('data-bs-theme', 'dark');
        iconSun.classList.remove('d-none');
        iconMoon.classList.add('d-none');
    } else if (window.matchMedia('(prefers-color-scheme: light)').matches) {
        html.setAttribute('data-bs-theme', 'light');
        iconSun.classList.add('d-none');
        iconMoon.classList.remove('d-none');
    }

    document.getElementById('themeToggle').addEventListener('click', function() {
        const isDark = html.getAttribute('data-bs-theme') === 'dark';
        html.setAttribute('data-bs-theme', isDark ? 'light' : 'dark');
        iconSun.classList.toggle('d-none');
        iconMoon.classList.toggle('d-none');
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
        animateGauge();
    });

    // Sidebar mobile toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    document.getElementById('menuToggle').addEventListener('click', function() {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });

    // Sidebar nav active
    document.querySelectorAll('.nav-sub .nav-sidebar-item, a.nav-sidebar-item:not([data-bs-toggle])').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.nav-sidebar-item').forEach(function(i) { i.classList.remove('active'); });
            this.classList.add('active');
            if (window.innerWidth < 992) {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            }
        });
    });

    // Bar Chart
    var chartData = [
        { label: 'Jan', value: 142 },
        { label: 'Feb', value: 320 },
        { label: 'Mar', value: 186 },
        { label: 'Apr', value: 278 },
        { label: 'May', value: 165 },
        { label: 'Jun', value: 178 },
        { label: 'Jul', value: 265 },
        { label: 'Aug', value: 98 },
        { label: 'Sep', value: 195 },
        { label: 'Oct', value: 356 },
        { label: 'Nov', value: 258 },
        { label: 'Dec', value: 112 }
    ];
    var maxVal = Math.max.apply(null, chartData.map(function(d) { return d.value; }));
    var chartEl = document.getElementById('barChart');

    chartData.forEach(function(item) {
        var pct = (item.value / maxVal * 100);
        var col = document.createElement('div');
        col.className = 'bar-col';

        var bar = document.createElement('div');
        bar.className = 'bar';
        bar.setAttribute('data-value', item.value);
        bar.style.height = '0%';

        var label = document.createElement('span');
        label.className = 'bar-label';
        label.textContent = item.label;

        col.appendChild(bar);
        col.appendChild(label);
        chartEl.appendChild(col);

        setTimeout(function() {
            bar.style.height = pct + '%';
        }, 400);

        bar.addEventListener('mouseenter', function() {
            var tip = document.createElement('div');
            tip.className = 'bar-tip';
            tip.textContent = item.value + ' enrollments';
            this.appendChild(tip);
        });
        bar.addEventListener('mouseleave', function() {
            var t = this.querySelector('.bar-tip');
            if (t) t.remove();
        });
    });

    // Gauge Animation
    function animateGauge() {
        var path = document.getElementById('gaugePath');
        var total = 251.2;
        var pct = 75.55;
        var off = total - (total * pct / 100);
        path.style.transition = 'stroke-dashoffset 1.5s cubic-bezier(0.34,1.56,0.64,1)';
        path.style.strokeDashoffset = off;
    }
    setTimeout(animateGauge, 500);

    // Keyboard shortcut
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('.search-input-wrap input').focus();
        }
    });
</script>
</body>
</html>

