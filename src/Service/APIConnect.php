<?php


namespace App\Service;



use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class APIConnect
{

    private $apihash;
    private $params;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->params = $parameterBag;
        $hash = md5($this->params->get('marvelapi_key_ts') . $this->params->get('marvelapi_key_private') .  $this->params->get('marvelapi_key_public'));
        $this->apihash = $hash;
    }



    /**
     * @return mixed
     */
    public function getApihash()
    {
        return $this->apihash;
    }

    /**
     * @param mixed $apihash
     */
    public function setApihash($apihash): void
    {
        $this->apihash = $apihash;
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

}
