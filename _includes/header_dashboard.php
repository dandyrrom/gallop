<?php
// We might add session checks/logic here later if needed specifically for the header
// We assume session is already started by dashboard.php which includes this file.
// We also assume $fname is defined in dashboard.php before including this.
?>
<!-- Header for Dashboard -->
<div id="header-placeholder">
    <a href="dashboard.php" class="header-logo"><img src="assets/Gallop-Project-Logo.svg" alt="Gallop Logo" style="height: 40px;"></a>
    <div class="header-user">
         <span>Welcome, <?php echo isset($fname) ? htmlspecialchars($fname) : 'User'; ?></span> <!-- Use $fname from dashboard.php -->
         <!-- Logout button/link might go here later or in sidebar -->
    </div>
</div>

