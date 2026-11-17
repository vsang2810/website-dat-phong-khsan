<?php
declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();

        try {
            $dashboard = new Dashboard();
            $this->view('admin/dashboard', [
                'title' => 'Tổng quan',
                'stats' => $dashboard->statistics(),
                'recentBookings' => $dashboard->recentBookings(),
                'statusCounts' => $dashboard->bookingStatuses(),
            ]);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            http_response_code(500);
            $this->view('errors/database', ['title' => 'Lỗi dữ liệu'], 'auth');
        }
    }
}
