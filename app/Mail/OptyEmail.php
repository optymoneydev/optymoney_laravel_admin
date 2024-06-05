<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OptyEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $mailData;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        //
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $address = $this->mailData->to;
        $subject = $this->mailData->subject;
        $name = $this->mailData->name;
        // $cc = $this->mailData->cc;
        // $bcc = $this->mailData->bcc;
        $from = $this->mailData->from;
        if($this->mailData->attachment=="yes") {
            return $this->view($this->mailData->template)
                // ->text('email.laraemail_plain')
                ->from($from, $name)
                // ->cc($address, $name)
                // ->bcc($cc, $name)
                ->replyTo($from, $name)
                ->subject($subject)
                ->attachData($this->mailData->files->output(), $this->mailData->filename.'.pdf')
                ->with(['mailMessage' => $this->mailData]);
        } else {
            return $this->view($this->mailData->template)
                // ->text('email.laraemail_plain')
                ->from($from, $name)
                // ->cc($address, $name)
                // ->bcc($cc, $name)
                ->replyTo($from, $name)
                ->subject($subject)
                ->with(['mailMessage' => $this->mailData]);
        }
        
        
    }
}
