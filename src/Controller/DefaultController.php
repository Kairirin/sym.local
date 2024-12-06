<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        $nombre = 'María';
        $saludo = 'Buenos días';
        $nombres = ['Ana', 'Enrique', 'Laura', 'Pablo'];

        return $this->render('prueba.html.twig', [ //Seleccionamos el html que abriremos y enviamos las variables. También se podría mandar como array
            'nombre' => $nombre,
            'saludo' => $saludo,
            'nombres' => $nombres,
            'fecha' => new DateTime()
        ]);
    }

    public function index2()
    {
        return $this->render('prueba1.html.twig');
    }

    public function index1()
    {
        $nombre = 'Juan';
        $saludo = 'Buenos días a todos';
        $nombres = ['Ana', 'Enrique', 'Laura', 'Pablo'];
        return $this->render('prueba2.html.twig', [
            'nombre' => $nombre,
            'saludo' => $saludo,
            'nombres' => $nombres,
            'fecha' => new \DateTime()
        ]);
    }
}
