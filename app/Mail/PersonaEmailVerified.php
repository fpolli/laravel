<?php

namespace App\Mail;

use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PersonaEmailVerified extends Mailable
{
  use Queueable, SerializesModels;

  public string $person;
  public string $role;
  public string $org;
  /**
   * Create a new message instance.
   */
  public function __construct(User $recipient)
  {
    //
    $this->person = $recipient->formal_name ?? $recipient->full_name;
    switch ($recipient->getLevel()) {
      case 1:
        $this->role = 'Friend';
        break;
      case 2:
        $this->role = 'Patron';
        break;
      case 3:
        $this->role = 'Member';
        break;
      default:
        $this->role = 'Member';
        break;
    }
    $this->org = config('app.name');
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      from: new Address(config('mail.from.address'), config('mail.from.name')),
      subject: 'New Email Addded',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'mail.persona-email-verified',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array
  {
    return [];
  }
}
