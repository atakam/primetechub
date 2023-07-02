<?php
namespace App\Models;

use CodeIgniter\Model;

class ClientinvoicepaymentsModel extends Model {

    protected $table = 'ci_invoice_payments';

    protected $primaryKey = 'invoice_payment_id';

	// get all fields of table
    protected $allowedFields = ['invoice_payment_id','invoice_id','amount','payment_date','payment_method','recorded_by'];

	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = false;

}
?>
