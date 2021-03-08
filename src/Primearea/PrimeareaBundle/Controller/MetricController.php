<?php namespace Primearea\PrimeareaBundle\Controller;

use Primearea\PrimeareaBundle\Dictionary\MetricTypeDictionary;
use Primearea\PrimeareaBundle\Dto\Metric;
use Primearea\PrimeareaBundle\Exception\ControllerException;
use Primearea\PrimeareaBundle\Repository\MetricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MetricController extends Controller
{
    /**
     * @var MetricRepository
     */
    private $metricRepository;

    /**
     * @param MetricRepository $metricRepository
     */
    public function __construct(MetricRepository $metricRepository)
    {
        $this->metricRepository = $metricRepository;
    }

    public function create(Request $request, int $id, int $relateId)
    {
        try {
            $metricTypeDictionary = new MetricTypeDictionary();
            if (!$metricTypeDictionary->hasKey($id)) {
                throw new ControllerException(404);
            }
            $metric = Metric::create($id, $relateId, $request->getClientIp(), $request->server->get('HTTP_USER_AGENT'));
            $this->metricRepository->create($metric);
            $response = new JsonResponse();
        } catch (ControllerException $e) {
            $response = new JsonResponse([], $e->getCode());
        }

        return $response;
    }
}
