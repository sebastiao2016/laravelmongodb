<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades;

class newLaravelTips extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\stdClass $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->subject('Novo Email está no ar!');
        $this->to($this->user->email, $this->user->name);

        $this->cc('ajtalves@hotmail.com');

        $bcc = [];
        $bcc = null;
        $bcc[] = 'ajtalvesrp@gmail.com';
        $bcc[] = 'ana.alves@zankh.com.br';

        $this->bcc($bcc);
        
        //return $this->view('mail.newLaravelTips', ['user' => $this->user]);
        return $this->markdown('mail.newLaravelTips', ['user' => $this->user]);

    }
}
