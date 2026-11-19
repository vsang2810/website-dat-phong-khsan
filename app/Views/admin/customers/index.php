<?php
$esc = static fn($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$statusLabels = ['active'=>['Đang hoạt động','status-confirmed'],'inactive'=>['Ngừng hoạt động','status-pending_payment'],'blocked'=>['Đã khóa','status-cancelled']];
?>
<div class="page-heading">
 <div><span class="eyebrow">CUSTOMER MANAGEMENT</span><h1>Quản lý khách hàng</h1><p class="muted">Tra cứu hồ sơ, theo dõi lịch sử đặt phòng và quản lý trạng thái tài khoản.</p></div>
 <div class="heading-date"><span>TỔNG KHÁCH HÀNG</span><strong><?= number_format($result['total']) ?> tài khoản</strong></div>
</div>
<?php foreach(['success'=>'alert-success','error'=>'alert-error'] as $k=>$class): if(!empty($_SESSION['flash_'.$k])): ?><div class="<?= $class ?>"><?= $esc($_SESSION['flash_'.$k]) ?></div><?php unset($_SESSION['flash_'.$k]); endif; endforeach; ?>
<div class="panel booking-filter-panel">
 <form class="simple-filter" method="get" action="<?= BASE_URL ?>/admin/customers">
  <input type="search" name="q" value="<?= $esc($filters['q']) ?>" placeholder="Tìm theo tên, email hoặc số điện thoại">
  <select name="status"><option value="">Tất cả trạng thái</option><option value="active" <?= $filters['status']==='active'?'selected':'' ?>>Đang hoạt động</option><option value="inactive" <?= $filters['status']==='inactive'?'selected':'' ?>>Ngừng hoạt động</option><option value="blocked" <?= $filters['status']==='blocked'?'selected':'' ?>>Đã khóa</option></select>
  <button class="btn-primary" type="submit">Tìm kiếm</button><a class="btn-reset" href="<?= BASE_URL ?>/admin/customers">Đặt lại</a>
 </form>
</div>
<div class="panel booking-table-panel">
 <div class="panel-heading"><div><span class="eyebrow">CUSTOMER DIRECTORY</span><h2>Danh sách tài khoản khách hàng</h2></div><span class="subtle-tag">Trang <?= $result['page'] ?> / <?= $result['pages'] ?></span></div>
 <div class="table-wrap"><table class="booking-management-table"><thead><tr><th>KHÁCH HÀNG</th><th>LIÊN HỆ</th><th>SỐ ĐƠN</th><th>GIÁ TRỊ ĐẶT PHÒNG</th><th>TRẠNG THÁI</th><th>THAO TÁC</th></tr></thead><tbody>
 <?php if(!$result['items']): ?><tr><td colspan="6" class="empty-state">Chưa tìm thấy tài khoản khách hàng phù hợp.</td></tr><?php else: foreach($result['items'] as $c): $s=$statusLabels[$c['status']]??$statusLabels['inactive']; ?>
 <tr>
  <td><strong><?= $esc($c['full_name']) ?></strong><small>Tham gia <?= date('d/m/Y',strtotime($c['created_at'])) ?></small></td>
  <td><?= $esc($c['email']) ?><small><?= $esc($c['phone'] ?: 'Chưa cập nhật số điện thoại') ?></small></td>
  <td><?= (int)$c['booking_count'] ?> đơn<?php if($c['last_booking_at']): ?><small>Gần nhất <?= date('d/m/Y',strtotime($c['last_booking_at'])) ?></small><?php endif; ?></td>
  <td class="amount"><?= number_format((float)$c['booking_value'],0,',','.') ?> ₫</td>
  <td><span class="status <?= $s[1] ?>"><?= $s[0] ?></span></td>
  <td><div class="row-actions"><a class="table-action" href="<?= BASE_URL ?>/admin/customers/show?id=<?= (int)$c['id'] ?>">Chi tiết</a><a class="table-action" href="<?= BASE_URL ?>/admin/customers/edit?id=<?= (int)$c['id'] ?>">Sửa</a>
   <form method="post" action="<?= BASE_URL ?>/admin/customers/status" class="room-status-form" onsubmit="return confirm('Bạn có chắc muốn thay đổi trạng thái tài khoản này?')">
    <input type="hidden" name="_csrf" value="<?= $esc(Csrf::token()) ?>"><input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
    <select name="status" aria-label="Trạng thái khách hàng"><option value="active" <?= $c['status']==='active'?'selected':'' ?>>Hoạt động</option><option value="inactive" <?= $c['status']==='inactive'?'selected':'' ?>>Ngừng hoạt động</option><option value="blocked" <?= $c['status']==='blocked'?'selected':'' ?>>Khóa</option></select><button class="text-action" type="submit">Lưu</button>
   </form></div></td>
 </tr><?php endforeach; endif; ?></tbody></table></div>
 <?php if($result['pages']>1): ?><nav class="pagination"><?php for($p=1;$p<=$result['pages'];$p++): $query=http_build_query(array_merge($filters,['page'=>$p])); ?><a class="<?= $p===$result['page']?'current':'' ?>" href="<?= BASE_URL ?>/admin/customers?<?= $esc($query) ?>"><?= $p ?></a><?php endfor; ?></nav><?php endif; ?>
</div>
<div class="module-shortcut"><span>Liên kết nghiệp vụ</span><strong>Đặt phòng</strong><a href="<?= BASE_URL ?>/admin/bookings">Quản lý đơn đặt phòng →</a></div>
