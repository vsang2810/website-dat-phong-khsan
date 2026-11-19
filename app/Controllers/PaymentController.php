<?php
declare(strict_types=1);

final class PaymentController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $f = [
            'q'=>trim((string)($_GET['q']??'')),
            'status'=>(string)($_GET['status']??''),
            'method'=>(string)($_GET['method']??''),
            'from'=>(string)($_GET['from']??''),
            'to'=>(string)($_GET['to']??''),
        ];
        if (!in_array($f['status'],['pending','success','failed','refunded'],true)) $f['status']='';
        if (!in_array($f['method'],['online','pay_at_hotel'],true)) $f['method']='';
        foreach (['from','to'] as $key) if ($f[$key]!=='' && !preg_match('/^\d{4}-\d{2}-\d{2}$/',$f[$key])) $f[$key]='';
        $this->view('admin/payments/index',[
            'title'=>'Quản lý thanh toán','activeMenu'=>'payments','filters'=>$f,
            'result'=>(new Payment())->paginate($f,max(1,(int)($_GET['page']??1))),
        ]);
    }

    public function show(): void
    {
        Auth::requireAdmin();
        $payment=(new Payment())->find(max(0,(int)($_GET['id']??0)));
        if (!$payment) { http_response_code(404); $this->view('errors/404',['title'=>'Không tìm thấy giao dịch']); return; }
        $this->view('admin/payments/show',['title'=>'Chi tiết thanh toán','activeMenu'=>'payments','payment'=>$payment]);
    }

    public function confirmHotelPayment(): void
    {
        Auth::requireAdmin();
        $id=max(0,(int)($_POST['booking_id']??0));
        if (!Csrf::verify($_POST['_csrf']??null)) {
            $_SESSION['flash_error']='Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/payments');
        }
        try {
            // Reuse the booking workflow so payment record and booking state are updated atomically.
            (new Booking())->updateWorkflow($id,'confirm_hotel_payment',(int)(Auth::user()['id']??0));
            $_SESSION['flash_success']='Đã ghi nhận khách thanh toán tại quầy và xác nhận đơn.';
        } catch (Throwable $e) {
            $_SESSION['flash_error']=$e instanceof RuntimeException?$e->getMessage():'Không thể ghi nhận thanh toán.';
            if (!$e instanceof RuntimeException) error_log($e->getMessage());
        }
        $this->redirect('/admin/payments');
    }
}
