<?php
// controllers/expedientesController.php
require_once(__DIR__ . '/../models/StudentModel.php');
require_once(__DIR__ . '/../config/database.php');

class ExpedientesController {
    public function index() {
        global $conn;
        $studentModel = new StudentModel();
        $students = $studentModel->getAllStudents($conn);
        require_once(__DIR__ . '/../views/expedientesView.php');
    }
}

$expedientesController = new ExpedientesController();
$expedientesController->index();
?>