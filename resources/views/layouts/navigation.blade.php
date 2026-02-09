    <!-- 🔹 Member Navigation Menu (SSS-Style) -->
<nav class="member-nav border-bottom py-2">
    <div class="container d-flex justify-content-start">
        <ol class="menu-inner">
            <li class="main menu-item">
                <a href="/dashboard" class="nav-link text-light me-4 menu-link">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>

            <li class="main menu-item dropdown">
                <!-- Member Information Dropdown -->
                <a href="#" class="nav-link dropdown-toggle text-light menu-link" id="memberInfoDropdown" role="button">
                    <i class="fas fa-user"></i> Member Information
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/profile" class="dropdown-item"><i class="fas fa-id-card"></i> Member Details</a></li>
                    <li><a class="dropdown-item" href="/member/contributions"><i class="fas fa-file-invoice-dollar"></i> My Contributions</a></li>
                </ul>
            </li>

            <li class="main menu-item">
                <a href="/logout" class="nav-link text-light menu-link">
                    <i class="fas fa-sign-out-alt"></i> Sign Out
                </a>
            </li>
        </ol>
    </div>
</nav>
<div class="date-time-container text-center">
    <i class="bi bi-calendar-event"></i>
    <span id="currentDateTime"></span>
</div>