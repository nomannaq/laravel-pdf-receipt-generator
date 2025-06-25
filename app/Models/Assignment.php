<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'creditor_name',
        'creditor_contact_person_name',
        'creditor_contact_person_email',
        'creditor_contact_person_phone',
        'debtor_name',
        'debtor_contact_person_name',
        'balance_capital',
        'balance_penalty_interest',
        'balance_debt_collection_fee',
        'balance_overpayment',
        'penalty_interest_rate',
        'total_for_due_date',
        'due_date'
    ];

    protected $casts = [
        'balance_capital' => 'decimal:2',
        'balance_penalty_interest' => 'decimal:2',
        'balance_debt_collection_fee' => 'decimal:2',
        'balance_overpayment' => 'decimal:2',
        'penalty_interest_rate' => 'decimal:4',
        'total_for_due_date' => 'decimal:2',
        'due_date' => 'date'
    ];

    public function procedures()
    {
        return $this->hasMany(Procedure::class);
    }

    public function getTemplateVariables()
    {
        return [
            'creditor_name' => $this->creditor_name,
            'creditor_contact_person_name' => $this->creditor_contact_person_name,
            'creditor_contact_person_email' => $this->creditor_contact_person_email,
            'creditor_contact_person_phone' => $this->creditor_contact_person_phone,
            'debtor_name' => $this->debtor_name,
            'debtor_contact_person_name' => $this->debtor_contact_person_name,
            'balance_capital' => number_format((float)$this->balance_capital, 2),
            'balance_penalty_interest' => number_format((float)$this->balance_penalty_interest, 2),
            'balance_debt_collection_fee' => number_format((float)$this->balance_debt_collection_fee, 2),
            'balance_overpayment' => number_format((float)$this->balance_overpayment, 2),
            'penalty_interest_rate' => number_format((float)$this->penalty_interest_rate, 4),
            'total_for_due_date' => number_format((float)$this->total_for_due_date, 2),
            'due_date' => $this->due_date ? $this->due_date->format('Y-m-d') : '',
        ];
    }
}
