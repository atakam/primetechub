<?php
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\SystemModel;
use App\Models\ConstantsModel;
use App\Models\InvoicesModel;
use App\Models\ClientinvoicepaymentsModel;
//$encrypter = \Config\Services::encrypter();
$SystemModel = new SystemModel();
$RolesModel = new RolesModel();
$UsersModel = new UsersModel();
$InvoicesModel = new InvoicesModel();
$ConstantsModel = new ConstantsModel();
$ClientinvoicepaymentsModel = new ClientinvoicepaymentsModel();

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();
$xin_system = erp_company_settings();

$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
if($user_info['user_type'] == 'staff'){
	$company_id = $user_info['company_id'];
} else {
	$company_id = $usession['sup_user_id'];
}
$unpaid = $InvoicesModel->where('company_id',$company_id)->where('status', 0)->countAllResults();
$paid = $InvoicesModel->where('company_id',$company_id)->where('status', 1)->countAllResults();

$paid_invoice = $InvoicesModel->where('company_id',$company_id)->like('created_at', 'match', 'both')->countAllResults();

$invoices = $InvoicesModel->where('company_id',$company_id)->findAll();
$invoices_ids = array();
$total_amount = 0;
foreach($invoices as $i) {
  array_push($invoices_ids, $i['invoice_id']);
  $total_amount += $i['grand_total'];
}
$payments = $ClientinvoicepaymentsModel->findAll();
$payment_amount = 0;
foreach($payments as $p) {
  if (in_array($p['invoice_id'], $invoices_ids)) {
    $payment_amount += $p['amount'];
  }
}
?>

<hr class="border-light m-0 mb-3">
<div class="row">
  <div class="col-sm-3">
    <div class="card prod-p-card background-pattern">
      <div class="card-body">
        <div class="row align-items-center m-b-0">
          <div class="col">
            <h6 class="m-b-5">Paid Amount</h6>
            <h3 class="m-b-0"><?= number_to_currency($payment_amount, $xin_system['default_currency'],null,0); ?></h3>
          </div>
          <div class="col-auto"> <i class="fas fa-money-bill-alt text-primary"></i> </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card prod-p-card bg-primary background-pattern-white">
      <div class="card-body">
        <div class="row align-items-center m-b-0">
          <div class="col">
            <h6 class="m-b-5 text-white">Paid Invoices</h6>
            <h3 class="m-b-0 text-white">
              <?= $paid;?>
            </h3>
          </div>
          <div class="col-auto"> <i class="fas fa-database text-white"></i> </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card prod-p-card bg-primary background-pattern-white">
      <div class="card-body">
        <div class="row align-items-center m-b-0">
          <div class="col">
            <h6 class="m-b-5 text-white">Due Amount</h6>
            <h3 class="m-b-0 text-white"><?= number_to_currency($total_amount - $payment_amount, $xin_system['default_currency'],null,0); ?></h3>
          </div>
          <div class="col-auto"> <i class="fas fa-dollar-sign text-white"></i> </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card prod-p-card background-pattern">
      <div class="card-body">
        <div class="row align-items-center m-b-0">
          <div class="col">
            <h6 class="m-b-5">Unpaid Invoices</h6>
            <h3 class="m-b-0">
              <?= $unpaid;?>
            </h3>
          </div>
          <div class="col-auto"> <i class="fas fa-tags text-primary"></i> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xl-6 col-md-12">
    <div class="row">
      <div class="col-xl-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <h6>Invoice Payments</h6>
            <div class="row d-flex justify-content-center align-items-center">
              <div class="col">
                <div id="paid-invoice-chart"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-md-12">
    <div class="row">
      <div class="col-xl-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <h6>Invoice Status</h6>
            <span>It takes continuous effort to maintain high.</span>
            <div class="row d-flex justify-content-center align-items-center">
              <div class="col">
                <div id="invoice-status-chart"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
