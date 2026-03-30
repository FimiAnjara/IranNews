<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                return [
                    'view' => 'auth/login.php',
                    'data' => ['error' => 'Email et mot de passe requis']
                ];
            }

            $user = $this->userModel->getByEmail($email);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                header('Location: ' . adminUrl('news-list'));
                exit;
            }

            return [
                'view' => 'auth/login.php',
                'data' => ['error' => 'Email ou mot de passe incorrect']
            ];
        }

        if (isset($_SESSION['user_id'])) {
            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        return [
            'view' => 'auth/login.php',
            'data' => []
        ];
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . backUrl('connexion'));
        exit;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                return [
                    'view' => 'auth/register.php',
                    'data' => ['error' => 'Tous les champs sont requis']
                ];
            }

            if ($password !== $confirm) {
                return [
                    'view' => 'auth/register.php',
                    'data' => ['error' => 'Les mots de passe ne correspondent pas']
                ];
            }

            if ($this->userModel->getByName($name)) {
                return [
                    'view' => 'auth/register.php',
                    'data' => ['error' => 'Ce nom est déjà utilisé']
                ];
            }

            if ($this->userModel->getByEmail($email)) {
                return [
                    'view' => 'auth/register.php',
                    'data' => ['error' => 'Cet email est déjà utilisé']
                ];
            }

            $this->userModel->create($name, $email, $password);

            return [
                'view' => 'auth/register.php',
                'data' => ['success' => 'Inscription réussie! Connectez-vous.']
            ];
        }

        return [
            'view' => 'auth/register.php',
            'data' => []
        ];
    }
}
