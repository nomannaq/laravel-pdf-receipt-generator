<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = [
        'name',
        'description',
        'assignment_id',
        'email_template_id',
        'sms_template_id',
        'pdf_template_id',
        'side_effects'
    ];

    protected $casts = [
        'side_effects' => 'array'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function emailTemplate()
    {
        return $this->belongsTo(Template::class, 'email_template_id');
    }

    public function smsTemplate()
    {
        return $this->belongsTo(Template::class, 'sms_template_id');
    }

    public function pdfTemplate()
    {
        return $this->belongsTo(Template::class, 'pdf_template_id');
    }
}
