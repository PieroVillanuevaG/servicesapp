<?php

namespace App\Http\Controllers;

use App\Mail\MailController;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendEmail($email, $objOverskull, $subject)
    {
        $view = "mail.mail";
        $send = \Mail::to($email)->send(new MailController($objOverskull, $subject, $view));
        if ($send) {
            return array("valor" => true, "msn" => "Mensaje enviado", "data" => []);
        } else {
            return array("valor" => false, "msn" => "Mensaje no enviado", "data" => []);
        }
    }
    public function sendEmailRecep($email, $objOverskull, $subject)
    {
        $view = "mail.mail_recept";
        $send = \Mail::to($email)->send(new MailController($objOverskull, $subject, $view));
        if ($send) {
            return array("valor" => true, "msn" => "Mensaje enviado", "data" => []);
        } else {
            return array("valor" => false, "msn" => "Mensaje no enviado", "data" => []);
        }
    }
}
