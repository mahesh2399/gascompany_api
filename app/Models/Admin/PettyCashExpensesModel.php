<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class PettyCashExpensesModel extends Model
{
    protected $table = 'petty_cash_expenses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'expense_amount',
        'expense_description',
        'petty_cash_entries_id',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function total_exp_amt($id, $current_date)
    {
        $sql = "SELECT sum(pces.expense_amount) as total_exp_amt FROM petty_cash_entries as pce, petty_cash_expenses as pces WHERE pces.petty_cash_entries_id = pce.id and pce.date = '$current_date' and pce.counter_id = $id GROUP by pces.petty_cash_entries_id ;";


        return $this->query($sql)->getResultArray();
    }
}