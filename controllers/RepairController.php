<?php
require_once __DIR__ . '/../models/Repair.php';

class RepairController {
    private $model;
    public function __construct($db) { $this->model = new Repair($db); }

    public function index() { return $this->model->getAll(); }
    public function store($data) { return $this->model->create($data); }
    public function update($id, $data) { return $this->model->updateStatus($id, $data); }
}
?>
