<?php

namespace App\BLL;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ImagenRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ImagenBLL
{
    private RequestStack $requestStack;
    private ImagenRepository $imagenRepository;
    private Security $security;

    public function __construct(RequestStack $requestStack, ImagenRepository $imagenRepository, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->imagenRepository = $imagenRepository;
        $this->security = $security;
    }
    public function getImagenesConOrdenacion(?string $ordenacion)
    {
        $usuarioLogueado = $this->security->getUser();

        if (!is_null($ordenacion)) { // Cuando se establece un tipo de ordenación específico
            $tipoOrdenacion = 'asc'; // Por defecto si no se había guardado antes en la variable de sesión
            $session = $this->requestStack->getSession(); // Abrir la sesión
            $usuarioLogueado = $this->security->getUser();
            $imagenesOrdenacion = $session->get('imagenesOrdenacion');
            if (!is_null($imagenesOrdenacion)) { // Comprobamos si ya se había establecido un orden
                if ($imagenesOrdenacion['ordenacion'] === $ordenacion) // Por si se ha cambiado de campo a ordenar
                {
                    if ($imagenesOrdenacion['tipoOrdenacion'] === 'asc')
                        $tipoOrdenacion = 'desc';
                }
            }
            $session->set('imagenesOrdenacion', [ // Se guarda la ordenación actual
                'ordenacion' => $ordenacion,
                'tipoOrdenacion' => $tipoOrdenacion
            ]);
        } else { // La primera vez que se entra se establece por defecto la ordenación por id ascendente
            $ordenacion = 'id';
            $tipoOrdenacion = 'asc';
        }
        return $this->imagenRepository->findImagenesConCategoria($ordenacion, $tipoOrdenacion, $usuarioLogueado);
    }
}
