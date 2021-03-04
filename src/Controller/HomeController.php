<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Entity\CurrencyTransactionEntity;
use App\Repository\CurrencyTransactionRepository;
use App\Service\CurrencyConverterService;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController{
    
    /**
     * @Route("/", name="index")
     */
    public function index(){
        $viewInput = [
            'version' => time(),
            'toCurrency' => [
                'USD',
                'PLN'
            ],
            'fromCurrency' => [
                'MXN',
                'ERN',
                'DZD',
                'CDF',
                'MAD',
                'SYP'
            ],
            'url' => [
                'currency_converter'=> $this->generateUrl('currency_converter'),
                'grid_conversion'   => $this->generateUrl('grid_conversion')
            ]
        ];
        return $this->render('index.html.twig', $viewInput);
    }

    /**
     * @Route("/transactions", name="transactions")
     */
    public function transactions(){
        $repository = $this->getDoctrine()->getRepository(CurrencyTransactionEntity::class);
        $currencyTransactions = $repository->findAll();
        $viewInput = [
            'transactions' => $currencyTransactions
        ];
        return $this->render('transactions.html.twig', $viewInput);
    }

    /**
     * @Route("/currency/convert", name="currency_converter")
     */
    public function currencyConvert(Request $request, CurrencyConverterService $currencyConverterService){
        $response = [
            'status' => 'error',
            'message' => 'No se ha podido realizar la transacción, intente de nuevo.',
            'data' => []
        ];

        if(!$request->isXmlHttpRequest()){
            return $this->json($response);    
        }

        if($request->getMethod() !== 'POST'){
            return $this->json($response);    
        }
        
        $toCurrency = $request->request->get('toCurrency');
        $currencyConverterService->setToCurrency($toCurrency);
        $currencyConverterService->setFromCurrency(['MXN', 'ERN', 'DZD', 'CDF', 'MAD', 'SYP']);
        // $currencyConverterService->setFromCurrency('MXN');
        $result = $currencyConverterService->convertCurrency();
        
        $entityManager = $this->getDoctrine()->getManager();
        foreach($result as $index => $value){
            $currencyArray = explode('_', $index);
            $currencyTransactionEntity = new CurrencyTransactionEntity();
            $currencyTransactionEntity->setToCurrency($currencyArray[0]);
            $currencyTransactionEntity->setFromCurrency($currencyArray[1]);
            $currencyTransactionEntity->setAmount($value);
            $currencyTransactionEntity->setCreatedAt();
            $entityManager->persist($currencyTransactionEntity);
            $entityManager->flush();
            $currencyTransactionEntity = null;
        }

        if($response){
            $response = [
                'status' => 'success',
                'message' => 'Se realizado la transacción.',
                'data' => $result
            ];
        }

        return $this->json($response);
    }

    /**
     * @Route("/grid/conversion", name="grid_conversion")
     */
    public function gridConversion(Request $request){
        $response = [
            'status'    => 'error',
            'message'   => 'No se ha podido realizar la transacción, intente de nuevo.',
            'data'      => []
        ];

        if(!$request->isXmlHttpRequest()){
            return $this->json($response);    
        }
        
        if($request->getMethod() !== 'POST'){
            return $this->json($response);    
        }

        $data = $request->request->get('currencyConversions');
        $arrayData = [];
        foreach($data as $key => $value){
            $keys = explode('_', $key);
            $arrayData[] = [
                'toCurrency'    => $keys[0],
                'fromCurrency'  => $keys[1],
                'value'         => number_format($value, 5)
            ];
        }

        $viewInput = [
            'currencyConversions' => $arrayData
        ];

        $view = $this->renderView('grid_conversion.html.twig', $viewInput);

        $response = [
            'status'    => 'success',
            'message'   => 'Se ha realizado la transacción.',
            'data'      => $view
        ];

        return $this->json($response);
    }
}
