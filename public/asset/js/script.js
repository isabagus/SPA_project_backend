src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
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
