<?php

namespace App\Controller\API;

use App\BLL\ImagenBLL;
use App\Controller\API\BaseApiController;
use App\Entity\Imagen;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ImagenApiController extends BaseApiController //Ya tendremos disponibles getContent y getResponse
{
    #[Route('/prueba', name: 'api_prueba', methods: ["GET"])]
    public function pruebaApi(): JsonResponse
    {
        return $this->json([
            'message' => 'Bienvenido al nuevo controlador!',
        ]);
    }

    #[Route('/imagenesapinueva', name: 'api_post_imagenes', methods: ['POST'])]
    public function post(Request $request, ImagenBLL $imagenBLL)
    {
        $data = $this->getContent($request);
        $imagen = $imagenBLL->nueva($data);
        return $this->getResponse($imagen, Response::HTTP_CREATED);
    }

    #[Route('/imagenesapi/{id}', name: 'api_get_imagen', requirements: ['id' => '\d'], methods: ['GET'])]
    public function getOne(Imagen $imagen, ImagenBLL $imagenBLL)
    {
        return $this->getResponse($imagenBLL->toArray($imagen));
    }

    /*     #[Route('/imagenesapi', name: 'api_get_imagenes', methods: ['GET'])] */
    #[Route('/imagenesapi/ordenadas/{order}', name: 'api_get_imagenes_ordenadas', methods: ['GET'])]
    public function getAll(Request $request, ImagenBLL $imagenBLL, $order = 'id') {
        $descripcion = $request->query->get('descripcion');
        $fechaInicial = $request->query->get('fechaInicial');
        $fechaFinal = $request->query->get('fechaFinal');

        $imagenes = $imagenBLL->getImagenes($order, $descripcion, $fechaInicial, $fechaFinal);
        return $this->getResponse($imagenes);
    }

    #[Route('/imagenesapi/{id}', name: 'api_delete_imagen', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(Imagen $imagen, ImagenBLL $imagenBLL)
    {
        $imagenBLL->delete($imagen);
        return $this->getResponse(null, Response::HTTP_NO_CONTENT); //Devuelve sin contenido
    }

    #[Route('/imagenesapi/{id}', name: 'api_update_imagen', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(Request $request, Imagen $imagen, ImagenBLL $imagenBLL)
    {
        $data = $this->getContent($request);
        $imagen = $imagenBLL->actualizaImagen($imagen, $data);
        return $this->getResponse($imagen, Response::HTTP_OK);
    }
}
