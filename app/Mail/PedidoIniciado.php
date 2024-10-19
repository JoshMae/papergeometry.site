<?php


namespace App\Mail;

use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf; // Importa la clase PDF correctamente
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generar el PDF a partir de la vista
        $pdf = Pdf::loadView('pdf.comprobante', [
            'pedido' => $this->pedido,
            'cliente' => $this->cliente,
        ])->output(); // Genera el contenido del PDF

        // Adjuntar el PDF generado al correo
        return $this->view('emails.pedido_iniciado')
                    ->attachData($pdf, 'comprobante_pedido.pdf', [
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'pedido' => $this->pedido,
                        'cliente' => $this->cliente,
                    ]);
    }
}
