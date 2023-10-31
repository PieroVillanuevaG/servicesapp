<?php

namespace App\Http\Controllers;

use App\Mail\MailController;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendEmail()
    {

        $email = "pierog@overskull.pe";
        $objOverskull = [];
        $subject = 'PRUEBA';
        $view = "mail.mail";
        $send = \Mail::to($email)->send(new MailController($objOverskull, $subject, $view));
        if ($send) {
            return array("valor" => true, "msn" => "Mensaje enviado", "data" => []);
        } else {
            return array("valor" => false, "msn" => "Mensaje no enviado", "data" => []);
        }
    }
}
