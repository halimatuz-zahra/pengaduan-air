<?php
require 'db.php';

function validate_user($username, $password, $role) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->execute([$username, $role]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return true;
    }
    return false;
}

function register_user($name, $email, $username, $password, $role) {
    global $pdo;
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $username, $password_hashed, $role]);
}

// Fungsi yang hilang
function get_user_by_username($username) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_user_id($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetchColumn();
}

function create_complaint($user_id, $location, $water_condition, $description, $evidence) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO complaints (user_id, location, water_condition, description, evidence) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$user_id, $location, $water_condition, $description, $evidence]);
}

function get_user_complaints($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM complaints WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_complaints() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM complaints");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function create_response($complaint_id, $username, $status, $response, $evidence) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO responses (complaint_id, responder, status, response, evidence) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$complaint_id, $username, $status, $response, $evidence]);

    $stmt = $pdo->prepare("UPDATE complaints SET status = ? WHERE complaint_id = ?");
    return $stmt->execute([$status, $complaint_id]);
}

function get_complaint_responses($complaint_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM responses WHERE complaint_id = ?");
    $stmt->execute([$complaint_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_complaint_by_id($complaint_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM complaints WHERE complaint_id = ?");
    $stmt->execute([$complaint_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function update_complaint($complaint_id, $location, $water_condition, $description, $evidence) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE complaints SET location = ?, water_condition = ?, description = ?, evidence = ? WHERE complaint_id = ?");
    return $stmt->execute([$location, $water_condition, $description, $evidence, $complaint_id]);
}

function delete_complaint($complaint_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM complaints WHERE complaint_id = ?");
    return $stmt->execute([$complaint_id]);
}

// Fungsi tambahan untuk profil pengguna
function update_user($user_id, $name, $email, $password) {
    global $pdo;
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, password = ? WHERE user_id = ?');
    return $stmt->execute([$name, $email, $password_hash, $user_id]);
}

function get_users_by_role($role) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = ?");
    $stmt->execute([$role]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
