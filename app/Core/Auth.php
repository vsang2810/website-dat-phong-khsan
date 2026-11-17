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

    public static function requireAdmin(): void
    {
        $user = self::user();

        if (!$user || !in_array($user['role'], ['admin', 'staff'], true)) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function attempt(string $email, string $password): bool
    {
        $stmt = Database::connection()->prepare(
            "SELECT id, full_name, email, password_hash, role, status
             FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || $user['status'] !== 'active'
            || !in_array($user['role'], ['admin', 'staff'], true)
            || empty($user['password_hash'])
            || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        unset($user['password_hash']);
        $_SESSION['user'] = $user;

        return true;
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
