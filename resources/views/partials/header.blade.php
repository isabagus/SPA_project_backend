

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