<?php include 'components/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Employee Lifecycle and Monitoring System</h1>
                <p>Comprehensive solution for managing employees, tracking attendance, assigning tasks, and monitoring performance - all in one platform.</p>
                <div class="hero-buttons">
                    <a href="login_admin.php" class="btn btn-primary">Admin Login</a>
                    <a href="login_employee.php" class="btn btn-secondary">Employee Login</a>
                </div>
            </div>
            <div class="hero-image">
                <div style="font-size: 8rem; opacity: 0.3;">👥</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <h2 class="section-title">Our Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <h3>👤 Employee Management</h3>
                <p>Add, update, and manage employee profiles with complete information including personal details, job roles, and employment history.</p>
            </div>
            <div class="feature-card">
                <h3>📊 Attendance Tracking</h3>
                <p>Monitor employee attendance with real-time check-in/check-out functionality and comprehensive attendance reports.</p>
            </div>
            <div class="feature-card">
                <h3>📋 Task Assignment</h3>
                <p>Assign tasks to employees, track progress, and manage deadlines with an intuitive task management system.</p>
            </div>
            <div class="feature-card">
                <h3>🏖️ Leave Management</h3>
                <p>Handle leave applications, approvals, and maintain leave balances with automated leave management workflows.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <div class="steps-grid">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Admin Adds Employees</h3>
                <p>Administrators easily add new employees to the system with all necessary information and assign appropriate roles and permissions.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Admin Assigns Tasks</h3>
                <p>Managers assign tasks to team members with clear deadlines, priorities, and detailed instructions for successful completion.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Employees Update Work</h3>
                <p>Employees mark attendance, update task progress, and manage their profiles through an intuitive employee dashboard.</p>
            </div>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="about-preview">
    <div class="container">
        <h2 class="section-title">About Our System</h2>
        <p>The Employee Lifecycle and Monitoring Management System is designed to streamline HR operations, enhance productivity, and provide comprehensive insights into workforce management. From onboarding to performance tracking, our system covers every aspect of employee management with modern technology and user-friendly interfaces.</p>
        <a href="about.php" class="btn btn-primary">Read More</a>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta">
    <div class="container">
        <h2>Start managing your employees now</h2>
        <a href="login_admin.php" class="btn btn-success btn-large">Login Now</a>
    </div>
</section>

<?php include 'components/footer.php'; ?>
