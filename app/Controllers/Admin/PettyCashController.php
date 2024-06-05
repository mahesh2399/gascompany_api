<?php

namespace App\Controllers\Admin;

use App\Models\Admin\PettyCashModel;
use App\Models\Admin\PettyCashExpensesModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class PettyCashController extends Controller
{
    use ResponseTrait;

    protected $pettyCashModel;
    protected $expensesModel;

    public function __construct()
    {
        $this->pettyCashModel = new PettyCashModel();
        $this->expensesModel = new PettyCashExpensesModel();
    }

    public function addPettyCash()
    {
        $data = $this->request->getJSON();
        $currentDate = date('Y-m-d');
        if (isset($data->id) && $data->id != "") {
            $insertData = [
                'petty_cash' => $data->petty_cash,
                'users_name' => $data->users_name,
                'counter_id' => $data->counter_id
            ];
            $this->pettyCashModel->set($insertData)->where('id', $data->id)->update();
            $message = [
                'status' => 200,
                'message' => 'Petty cash updated successfully.'
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        } else {
            $chk = $this->pettyCashModel->where(['counter_id' => $data->counter_id, 'date' => $currentDate])->findAll();
            if (count($chk) <= 0) {
                $insertData = [
                    'date' => $currentDate,
                    'petty_cash' => $data->petty_cash,
                    'users_name' => $data->users_name,
                    'counter_id' => $data->counter_id
                ];
                if ($this->pettyCashModel->insert($insertData)) {
                    $message = [
                        'status' => 200,
                        'message' => 'Petty cash created successfully.'
                    ];
                    return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);

                } else {
                    $message = [
                        'status' => 500,
                        'message' => 'Something when worng try'
                    ];
                    return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
                }
            } else {

                $message = [
                    'status' => 500,
                    'message' => "You can't add the same counter again  "
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobjct' => $message]);
            }
        }
    }

    public function updatePettyCash()
    {
        $pcCash = new PettyCashModel();

        $json = $this->request->getJSON();
        $counter_Id = isset($json->counterId) ? $json->counterId : null;
        $recon_message = isset($json->message) ? $json->message : null;
        $exit_amount = isset($json->exit_amount) ? $json->exit_amount : null;
        $currentDate = date('Y-m-d');


        $insertData = [
            "counter_id" => $counter_Id,
            "exit_amount" => $exit_amount,
            "recon_status" => $recon_message,

        ];


        $data = $pcCash->set($insertData)->where(['counter_id' => $counter_Id, 'date' => $currentDate])->update();

        $data = "paymant updated successfully";
        $message = [
            'message' => ' updated successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);


    }

    public function editPettyCash()
    {
        $pcCash = new PettyCashModel();

        $json = $this->request->getJSON();
        $id = isset($json->id) ? $json->id : null;
        $petty_cash = isset($json->petty_cash) ? $json->petty_cash : null;
        $counter_id = isset($json->counter_id) ? $json->counter_id : null;

        $currentDate = date('Y-m-d');


        $insertData = [
            "id" => $id,
            "petty_cash" => $petty_cash,
          

        ];


        $data = $pcCash->set($insertData)->where(['id' => $id, 'date' => $currentDate])->update();

        $data = "paymant updated successfully";
        $message = [
            'message' => ' updated successfully!',
            'status' => 200
        ];
        return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);


    }

    public function getPettyCash()
    {
        $entrymodel = new PettyCashModel();
        $expensesmodel = new PettyCashExpensesModel();
        $counter_id = $this->request->getGet('counter_id');
        $currentDate = date('Y-m-d');
        $entryamt = $entrymodel->where(['counter_id' => $counter_id, 'date' => $currentDate])->first();
        if ($entryamt) {
            $total_exp = $expensesmodel->total_exp_amt($counter_id, $currentDate);
            $data['counter_id'] = $entryamt['counter_id'];
            $data['date'] = date('d-m-Y', strtotime($entryamt['date']));
            $data['id'] = $entryamt['id'];

            $data['recon_status'] = $entryamt['recon_status'];
            $data['users_name'] = $entryamt['users_name'];
            $data['entry_amount'] = $entryamt['petty_cash'];
            // $data['total_exp'] = $total_exp[0]['total_exp_amt'];
            $data['total_exp'] = isset($total_exp[0]['total_exp_amt']) ? $total_exp[0]['total_exp_amt'] : 0;

            $data['balance_amt'] = $data['entry_amount'] - $data['total_exp'];
            $data['exp_list'] = $expensesmodel->where('petty_cash_entries_id', $entryamt['id'])->findAll();
            $message = [
                'status' => 200,
                'message' => 'Successfully fetched data'
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => [$data]]);
        } else {
            $message = [
                'status' => 400,
                'message' => 'No data found'
            ];

            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }

    }

    public function addExpense()
    {
        $data = $this->request->getJSON();

        if (isset($data->id) && $data->id != "") {
            $insertData = [
                'expense_amount' => $data->expense_amount,
                'expense_description' => $data->expense_description
            ];
            $this->expensesModel->set($insertData)->where('id', $data->id)->update();
            $message = [
                'status' => 200,
                'message' => 'Expense updated successfully.'
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        } else {
            $currentDate = date('Y-m-d');
            $pettycashDetails = $this->pettyCashModel->where(['counter_id' => $data->counter_id, 'date' => $currentDate])->first();
            $insertData = [
                'expense_amount' => $data->expense_amount,
                'expense_description' => $data->expense_description,
                'petty_cash_entries_id' => $pettycashDetails['id']
            ];
            $this->expensesModel->insert($insertData);

            $message = [
                'status' => 200,
                'message' => 'Expense added successfully.'
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        }
    }

    public function getExpenses()
    {
        $entrymodel = new PettyCashModel();
        $expensesmodel = new PettyCashExpensesModel();
        $counter_id = $this->request->getGet('counter_id');
        $currentDate = date('Y-m-d');
        $entryamt = $entrymodel->where(['counter_id' => $counter_id, 'date' => $currentDate])->first();

        if ($entryamt) {
            $data = $expensesmodel->where('petty_cash_entries_id', $entryamt['id'])->findAll();

            $total_exp = $expensesmodel->total_exp_amt($counter_id, $currentDate);
            $message = [
                'status' => 200,
                'message' => 'Successfully fetched data'
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $data]);
        } else {
            $message = [
                'status' => 400,
                'message' => 'No data found'
            ];

            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }

    public function getBalance()
    {
        $initialAmount = $this->pettyCashModel->orderBy('id', 'asc')->first()['petty_cash'];

        $totalExpenses = $this->expensesModel->selectSum('expense_amount')->first()['expense_amount'];

        $balanceAmount = $initialAmount - $totalExpenses;

        return $this->respond(['balance_amount' => $balanceAmount]);
    }

    public function getAllTransactions()
    {
        $transactions = $this->expensesModel->findAll();
        return $this->respond($transactions);
    }

    public function getPettyCash1()
    {
        try {
            $entryModel = new PettyCashModel();
    
            // Get the name from the request body
            $request = service('request');
            $name = $request->getJSON()->name ?? null;
    
            // Base query
            $query = "SELECT * FROM petty_cash_entries";
    
            // If name is provided, add WHERE clause for filtering
            if ($name !== null) {
                // Sanitize the input to prevent SQL injection
                $name = $entryModel->escapeString($name);
                $query .= " WHERE users_name LIKE '%$name%'";
            }
    
            // Order by date in descending order
            $query .= " ORDER BY date DESC";
    
            // Execute the query
            $result = $entryModel->query($query)->getResult();
    
            foreach ($result as &$row) {
                // Modify date format
                $row->date = date('d-m-Y', strtotime($row->date));
            }
    
            // Prepare response
            if (!empty($result)) {
                $response = [
                    'data' => $result,
                    'messageobject' => [
                        'message' => $name !== null ? 'Petty cash entries found for the given name.' : 'All petty cash entries retrieved successfully.',
                        'status' => 200
                    ]
                ];
                return $this->respond($response, 200);
            } else {
                $response = [
                    'messageobject' => [
                        'message' => $name !== null ? 'No petty cash entries found for the given name.' : 'No petty cash entries found.',
                        'status' => 404
                    ]
                ];
                return $this->respond($response, 404);
            }
        } catch (\Exception $e) {
            $error_message = [
                'messageobject' => [
                    'message' => 'Server Error',
                    'status' => 500
                ]
            ];
            return $this->failServerError('Server Error');
        }
    }
    
    
    



    public function editExpense($expenseId)
    {
        $data = $this->request->getJSON();

        $expense = $this->expensesModel->find($expenseId);

        if (!$expense) {
            return $this->respond(['status' => false, 'message' => 'Expense not found.'], 404);
        }

        $newBalance = $expense['balance_amount'] + $expense['expense_amount'] - $data->expense_amount;

        $updateData = [
            'expense_amount' => $data->expense_amount,
            'expense_description' => $data->expense_description,
            'balance_amount' => $newBalance
        ];

        if ($this->expensesModel->update($expenseId, $updateData)) {
            return $this->respond(['status' => true, 'message' => 'Expense updated successfully.', 'balance_amount' => $newBalance]);
        } else {
            return $this->respond(['status' => false, 'message' => 'Failed to update expense.'], 500);
        }
    }

}
