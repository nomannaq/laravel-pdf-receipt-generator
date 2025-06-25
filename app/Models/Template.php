<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name',
        'type',
        'content',
        'email_subject',
        'pdf_attachments',
        'description',
        'metadata',
        'is_default'
    ];

    protected $casts = [
        'metadata' => 'array',
        'pdf_attachments' => 'array',
        'is_default' => 'boolean'
    ];

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeDefaults($query)
    {
        return $query->where('is_default', true);
    }

    public function procedures()
    {
        return $this->hasMany(Procedure::class, 'email_template_id')
            ->orWhere('sms_template_id', $this->id)
            ->orWhere('pdf_template_id', $this->id);
    }

    public function emailProcedures()
    {
        return $this->hasMany(Procedure::class, 'email_template_id');
    }

    public function smsProcedures()
    {
        return $this->hasMany(Procedure::class, 'sms_template_id');
    }

    public function pdfProcedures()
    {
        return $this->hasMany(Procedure::class, 'pdf_template_id');
    }

    public function isLinkedToProcedure()
    {
        return Procedure::where('email_template_id', $this->id)
            ->orWhere('sms_template_id', $this->id)
            ->orWhere('pdf_template_id', $this->id)
            ->exists();
    }

    public function getLinkedProcedures()
    {
        return Procedure::where('email_template_id', $this->id)
            ->orWhere('sms_template_id', $this->id)
            ->orWhere('pdf_template_id', $this->id)
            ->get();
    }

    public function renderWithVariables($assignment)
    {
        $variables = $assignment instanceof Assignment ? $assignment->getTemplateVariables() : $assignment;
        
        $content = $this->content;
        $subject = $this->email_subject;

        // Replace variables in content
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
            if ($subject) {
                $subject = str_replace('{{' . $key . '}}', $value, $subject);
            }
        }

        return [
            'content' => $content,
            'subject' => $subject,
            'original_content' => $this->content,
            'original_subject' => $this->email_subject
        ];
    }
}
