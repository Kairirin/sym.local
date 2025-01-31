<?php

namespace App\BLL;

use App\Entity\Categoria;
use App\Entity\Imagen;
use App\Entity\User;
use App\Repository\ImagenRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImagenBLL extends BaseBLL
{
    protected ImagenRepository $imagenRepository;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        RequestStack $requestStack,
        Security $security,
        ImagenRepository $imagenRepository
    ) {
        parent::__construct($em, $validator, $requestStack, $security);
        $this->imagenRepository = $imagenRepository;
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

    /*     public function nueva(array $data)
    {
        $imagen = new Imagen();
        $imagen->setNombre($data['nombre']);
        $imagen->setDescripcion($data['descripcion']);
        $imagen->setNumVisualizaciones($data['numVisualizaciones']);
        $imagen->setNumLike($data['numLike']);
        $imagen->setNumDownload($data['numDownload']);
        // El id de la categoria, la tenemos que busar en su BBDD
        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);
        $imagen->setCategoria($categoria);
        $fecha = DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $imagen->setFecha($fecha);
        $usuario = $this->em->getRepository(User::class)->find($data['usuario']);
        $imagen->setUsuario($usuario);
        return $this->guardaValidando($imagen);
    } */

    public function nueva(array $data)
    {
        $imagen = new Imagen();
        return $this->actualizaImagen($imagen, $data);
    }

    public function toArray(Imagen $imagen)
    {
        if (is_null($imagen))
            return null;
        return [
            'id' => $imagen->getId(),
            'nombre' => $imagen->getNombre(),
            'descripcion' => $imagen->getDescripcion(),
            'categoria' => $imagen->getCategoria()->getNombre(),
            'numLikes' => $imagen->getNumLike(),
            'numVisualizaciones' => $imagen->getNumVisualizaciones(),
            'numDownload' => $imagen->getNumDownload(),
            'fecha' => is_null($imagen->getFecha()) ? '' : $imagen->getFecha()->format('d/m/Y'),
            'usuario' => $imagen->getUsuario()->getId()
        ];
    }

    public function getImagenes(?string $order, ?string $descripcion, ?string $fechaInicial, ?string $fechaFinal)
    {
        $imagenes = $this->em->getRepository(Imagen::class)->findImagenes($order, $descripcion, $fechaInicial, $fechaFinal, $usuario=null);
        return $this->entitiesToArray($imagenes);
    }

    public function actualizaImagen(Imagen $imagen, array $data)
    {
        $imagen->setNombre($data['nombre']);
        $imagen->setDescripcion($data['descripcion']);
        $imagen->setNumVisualizaciones($data['numVisualizaciones']);
        $imagen->setNumLike($data['numLike']);
        $imagen->setNumDownload($data['numDownload']);
        // El id de la categoria, la tenemos que busar en su BBDD a partir del nombre de la seleccionada
        $categoria = $this->em->getRepository(Categoria::class)->find($data['categoria']);
        $imagen->setCategoria($categoria);
        $fecha = DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $imagen->setFecha($fecha);
        $usuario = $this->em->getRepository(User::class)->find($data['usuario']);
        $imagen->setUsuario($usuario);
        return $this->guardaValidando($imagen);
    }
}
