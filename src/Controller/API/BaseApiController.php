<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BaseApiController extends AbstractController
{
    public function getContent(Request $request)
    {
        $data = json_decode($request->getContent(), true); //Recibimos datos en tipo JSON, se codifican y obtenemos un array
        if (is_null($data))
            throw new BadRequestHttpException('No se han recibido los datos');
        return $data;
    }

    protected function getResponse(array $data = null, $statusCode = Response::HTTP_OK)
    {
        $response = new JsonResponse(); //Aquí al contrario, se le pasa un array asociativo y hacemos la conversión
        if (!is_null($data)) {
            $result['data'] = $data; // Siempre se debe devolver el resultado dentro de una clave
            $response->setContent(json_encode($result));
        }
        $response->setStatusCode($statusCode);
        return $response;
    }
}
