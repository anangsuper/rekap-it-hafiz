<?php
require_once __DIR__ . '/../models/Maintenance.php';

class MaintenanceController {
    private $model;
    public function __construct($db) { $this->model = new Maintenance($db); }

    public function index() { return $this->model->getAll(); }
    public function store($data) { return $this->model->create($data); }
}
?>
