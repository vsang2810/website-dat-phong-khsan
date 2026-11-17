<?php
declare(strict_types=1);

abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'admin'): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';
        $layoutFile = BASE_PATH . '/app/Views/layouts/' . $layout . '.php';

        if (!is_file($viewFile) || !is_file($layoutFile)) {
            http_response_code(500);
            exit('Không tìm thấy giao diện.');
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        require $layoutFile;
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }
}
