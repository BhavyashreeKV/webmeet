<?php 

use Dimafe6\BankID\Model\CollectResponse;
use Dimafe6\BankID\Model\OrderResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use phpDocumentor\Reflection\Types\Object_;

class BankidServices
{
    /** @var Client $client Guzzle http client */
    private $client;

    /** @var string $apiUrl BankID API base url */
    private $apiUrl;

    /** @var array $options Guzzle client options. @see http://docs.guzzlephp.org/en/stable/request-options.html */
    private $options;

    /** @var string $endUserIp The user IP address as seen by RP. String. IPv4 and IPv6 is allowed */
    private $endUserIp;

    /**
     * BankIDService constructor.
     * @param string $apiUrl
     * @param string $endUserIp
     * @param array $options
     */
    public function __construct($apiUrl, $endUserIp, $options = [])
    {
        $this->apiUrl    = $apiUrl;
        $this->endUserIp = $endUserIp;

        $options['base_uri'] = $apiUrl;
        $options['json']     = true;

        $this->options = $options;

        $this->client = new Client($this->options);
    }
    
    /**
     * @param string $personalNumber The personal number of the user. String. 12 digits. Century must be included.
     * @return OrderResponse
     * @throws ClientException
     */
    public function getAuthResponse($personalNumber)
    {
        $parameters = [
            'personalNumber' => $personalNumber,
            'endUserIp'      => $this->endUserIp,
            'requirement'    => [
                'allowFingerprint' => true,
            ],
        ];
        try {
            $responseData = $this->client->post('auth', ['json' => $parameters]);
            
            $response = new OrderResponse($responseData);
            
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response['responseCode'] = $e->getResponse()->getstatusCode();
            $resperror = explode('response:',$e->getMessage());
            $response['message'] = json_decode($resperror[1]);

            $response = (Object)$response;
        }
        
        return $response;
    }


    /**
     * @param string $personalNumber The personal number of the user. String. 12 digits. Century must be included.
     * @param string $userVisibleData The text to be displayed and signed.
     * @param string $userHiddenData Data not displayed to the user
     * @return OrderResponse
     * @throws ClientException
     */
    public function getSignResponse($personalNumber, $userVisibleData, $userHiddenData = '')
    {
        $parameters = [
            'personalNumber'  => $personalNumber,
            'endUserIp'       => $this->endUserIp,
            'userVisibleData' => base64_encode($userVisibleData),
            'requirement'     => [
                'allowFingerprint' => true,
            ],
        ];

        if (!empty($userHiddenData)) {
            $parameters['userNonVisibleData'] = base64_encode($userHiddenData);
        }

        try {
        $responseData = $this->client->post('sign', ['json' => $parameters]);
        $response = new OrderResponse($responseData);
        
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response['responseCode'] = $e->getResponse()->getstatusCode();
            $resperror = explode('response:',$e->getMessage());
            $response['message'] = json_decode($resperror[1]);

            $response = (Object)$response;
        }
        
        return $response;
    }

    /**
     * @param string $orderRef Used to collect the status of the order.
     * @return CollectResponse
     * @throws ClientException
     */
    public function collectResponse($orderRef)
    {
        try {

        $responseData = $this->client->post('collect', ['json' => ['orderRef' => $orderRef]]);

        $response = new OrderResponse($responseData);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response['responseCode'] = $e->getResponse()->getstatusCode();
            $resperror = explode('response:',$e->getMessage());
            $response['message'] = json_decode($resperror[1]);
            $response = (Object)$response;
        }
              
        return $response;
    }

    /**
     * @param string $orderRef Used to collect the status of the order.
     * @return bool
     * @throws ClientException
     */
    public function cancelOrder($orderRef)
    {
        $responseCode = $this->client->post('cancel', ['json' => ['orderRef' => $orderRef]])->getStatusCode();

        return $responseCode === 200;
    }
}