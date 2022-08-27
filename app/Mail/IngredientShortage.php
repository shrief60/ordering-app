<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IngredientShortage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ingredient;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('shriefanwer60@gmail.com' , 'Sherif')->view('emails.Ingreient_shortage');//->text('emails.Ingreient_shortage');
    }
}
