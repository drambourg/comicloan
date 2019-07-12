<?php


namespace App\Service;



use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class APIConnect
{

    const BASE_URI_CHARACTER ='/v1/public/characters';
    const BASE_URI_COMIC ='/v1/public/comics';
    const BASE_URI_CREATOR ='/v1/public/creators';
    private $apiurl;
    private $apiTS;
    private $apiPrivateKey;
    private $apiPublicKey;
    private $params;


    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->params = $parameterBag;
        $this->apiurl = $parameterBag->get('marvelapi_url');
        $this->apiTS =$this->params->get('marvelapi_key_ts');
        $this->apiPrivateKey =$this->params->get('marvelapi_key_private');
        $this->apiPublicKey =$this->params->get('marvelapi_key_public');
    }


    public function baseParamsConnect() :array
    {
        return [
            'ts' => $this->getApiTS(),
            'apikey' => $this->getApiPublicKey(),
            'hash' => $this->getApihash(),
            ];
    }
    /**
     * @return string
     */
    public function getApihash()
    {
        return  md5($this->getApiTS() . $this->getApiPrivateKey() .  $this->getApiPublicKey());
    }


    /**
     * @return ParameterBagInterface
     */
    public function getParams(): ParameterBagInterface
    {
        return $this->params;
    }

    /**
     * @param ParameterBagInterface $params
     */
    public function setParams(ParameterBagInterface $params): void
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getApiurl()
    {
        return $this->apiurl;
    }

    /**
     * @param string $apiurl
     */
    public function setApiurl($apiurl): void
    {
        $this->apiurl = $apiurl;
    }

    /**
     * @return integer
     */
    public function getApiTS()
    {
        return $this->params->get('marvelapi_key_ts');
    }

    /**
     * @return mixed
     */
    public function getApiPrivateKey()
    {
        return $this->apiPrivateKey;
    }

    /**
     * @param mixed $apiPrivateKey
     */
    public function setApiPrivateKey($apiPrivateKey): void
    {
        $this->apiPrivateKey = $apiPrivateKey;
    }

    /**
     * @return mixed
     */
    public function getApiPublicKey()
    {
        return $this->apiPublicKey;
    }

    /**
     * @param mixed $apiPublicKey
     */
    public function setApiPublicKey($apiPublicKey): void
    {
        $this->apiPublicKey = $apiPublicKey;
    }


}
