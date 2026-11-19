<?php
declare(strict_types=1);

final class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function id(): ?int
    {
        return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
    }

    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user !== null && in_array($user['role'], ['admin', 'staff'], true);
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/admin';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireCustomer(): void
    {
        if (!self::check()) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function attempt(string $email, string $password, ?array $roles = null): bool
    {
        $stmt = Database::connection()->prepare(
            "SELECT id, full_name, email, phone, gender, avatar, password_hash, role, status
             FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || $user['status'] !== 'active') {
            return false;
        }

        if ($roles !== null && !in_array($user['role'], $roles, true)) {
            return false;
        }

        if (empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        self::loginUser($user);
        return true;
    }

    public static function loginUser(array $user): void
    {
        session_regenerate_id(true);
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }
}
