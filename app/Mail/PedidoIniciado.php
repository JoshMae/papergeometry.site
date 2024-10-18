<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PedidoIniciado extends Mailable
{
    public $pedido;
    public $cliente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pedido, $cliente)
    {
        $this->pedido = $pedido;
        $this->cliente = $cliente;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Pedido Iniciado',
        );
    }

    /**
     * Get the content definition for the mailable.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.pedido_iniciado',
        );
    }
}
