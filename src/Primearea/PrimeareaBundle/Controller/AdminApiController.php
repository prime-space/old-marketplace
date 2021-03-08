<?php namespace Primearea\PrimeareaBundle\Controller;

use Primearea\PrimeareaBundle\AdminApi;
use Primearea\PrimeareaBundle\Exception\ControllerException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminApiController extends Controller
{
    private $adminApi;
    private $secret;

    public function __construct(AdminApi $adminApi, string $secret)
    {
        $this->adminApi = $adminApi;
        $this->secret = $secret;
    }

    public function action(Request $request, string $method)
    {
        try {
            $auth = $request->headers->get('authorization');
            if (null === $auth || $auth !== "Bearer {$this->secret}") {
                throw new ControllerException(403);
            }

            $function = [$this->adminApi, "{$method}Method"];
            if (!is_callable($function)) {
                throw new ControllerException(404);
            }

            $result = call_user_func($function, $request->request);

            $response = new JsonResponse($result);
        } catch (ControllerException $e) {
            $response = new JsonResponse([], $e->getCode());
        }

        return $response;
    }
}
