<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

switch ($_SESSION['role']) {
    case 'Super Admin':
        header("Location: pages/superAdmin.php");
        break;
    case 'Project Manager':
        header("Location: pages/projectManager.php");
        break;
    case 'Team Member':
        header("Location: pages/teamMember.php");
        break;
    default:
        header("Location: logout.php");
        break;
}
exit();
?>