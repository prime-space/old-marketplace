<?php namespace Primearea\MerchantBundle\Controller;

use Primearea\MerchantBundle\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Well\DBBundle\Exception\EntryNotFoundException;

class PaymentController extends Controller
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function checkPaymentAction(Request $request, int $id)
    {
        try {
            $status = $this->paymentRepository->getPaymentStatus($id);
            $response = new JsonResponse(['status' => $status]);
        } catch (EntryNotFoundException $e) {
            $response = new JsonResponse([], 404);
        }

        return $response;
    }
}
