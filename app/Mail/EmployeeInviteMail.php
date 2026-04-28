<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $loginUrl;

    public function __construct(
        public Company $company,
        public User $user,
        public string $plainPassword,
    ) {
        $this->loginUrl = route('login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your account at '.$this->company->company_name.' — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.employee-invite',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
