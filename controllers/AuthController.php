<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct($pdo) {
        $this->usuarioModel = new Usuario($pdo);
    }

    /**
     * Procesa el intento de inicio de sesión.
     */
    public function login($username, $password) {
        $errors = [];

        if (empty($username)) {
            $errors[] = "El nombre de usuario es obligatorio";
        }
        if (empty($password)) {
            $errors[] = "La contraseña es obligatoria";
        }

        if (empty($errors)) {
            $user = $this->usuarioModel->buscarPorUsername($username);

            if ($user) {
                // Verificación de contraseña con soporte para MD5 heredado
                if (password_verify($password, $user['password']) || md5($password) === $user['password']) {
                    $_SESSION['userId'] = $user['user_id'];
                    return ['success' => true];
                } else {
                    $errors[] = "Combinación de usuario/contraseña incorrecta";
                }
            } else {
                $errors[] = "El nombre de usuario no existe";
            }
        }

        return ['success' => false, 'errors' => $errors];
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout() {
        session_unset();
        session_destroy();
    }
}
