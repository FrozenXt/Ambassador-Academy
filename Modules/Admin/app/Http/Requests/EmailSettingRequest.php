<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        if ($this->isMethod('post') && $this->routeIs('admin.email-settings.test')) {
            return [
                'test_email' => 'required|email',
            ];
        }

        return [
            'mailer'       => 'required|in:smtp,sendmail,mailgun,ses,postmark',
            'host'         => 'required|string|max:255',
            'port'         => 'required|integer|min:1|max:65535',
            'username'     => 'required|string|max:255',


            'password'     => $this->isMethod('post')
                ? 'required|string|max:255'
                : 'nullable|string|max:255',

            'encryption'   => 'required|in:tls,ssl,none',
            'from_address' => 'required|email',
            'from_name'    => 'required|string|max:255',
            'is_active'    => 'sometimes|boolean',
            'admin_mail'   => ['nullable', 'string'],
            'cc_mail'    => ['nullable', 'string'],
            'bcc_mail'   => ['nullable', 'string'],
        ];
    }
}
