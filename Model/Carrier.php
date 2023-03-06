<?php

/**
 * Serverless Shipping
 *
 * Calculate shipping rates using a serverless function.
 *
 * @package ImDigital\ServerlessShipping
 * @author Igor Ludgero Miura <igor@imdigital.com>
 * @copyright Copyright (c) 2023 Imagination Media (https://www.imdigital.com/)
 * @license Private
 */

declare(strict_types=1);

namespace ImDigital\ServerlessShipping\Model;

use ImDigital\Serverless\Api\Data\CloudProviderInterface;
use ImDigital\Serverless\Api\Data\ServerlessFunctionInterface;
use ImDigital\Serverless\Model\ServerlessFunctionConfigRepository;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\CollectionFactory
    as ServerlessFunctionCollectionFactory;
use ImDigital\ServerlessShipping\Helper\ShippingHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

class Carrier extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    // @codingStandardsIgnoreStart
    protected $_code = "serverless";
    // @codingStandardsIgnoreEnd

    /**
     * @var MethodFactory
     */
    protected MethodFactory $methodFactory;

    /**
     * @var ResultFactory
     */
    protected ResultFactory $resultFactory;

    /**
     * @var ServerlessFunctionCollectionFactory
     */
    protected ServerlessFunctionCollectionFactory $serverlessFunctionCollectionFactory;

    /**
     * @var ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository
     */
    protected ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository;

    /**
     * @var ScopeConfig $scopeConfig
     * @var ErrorFactory $rateErrorFactory
     * @var LoggerInterface $logger
     * @var MethodFactory $methodFactory
     * @var ResultFactory $rateResultFactory
     * @var ServerlessFunctionCollectionFactory $serverlessFunctionCollectionFactory
     * @var ShippingHelper $shippingHelper
     * @var array $data
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        MethodFactory $methodFactory,
        ResultFactory $rateResultFactory,
        ServerlessFunctionCollectionFactory $serverlessFunctionCollectionFactory,
        ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->methodFactory = $methodFactory;
        $this->resultFactory = $rateResultFactory;
        $this->serverlessFunctionCollectionFactory = $serverlessFunctionCollectionFactory;
        $this->serverlessFunctionConfigRepository = $serverlessFunctionConfigRepository;
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return boolean
     */
    public function isTrackingAvailable(): bool
    {
        return (int)$this->getConfigData('active') === 1 && (string)$this->getConfigData('function');
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('title')];
    }

    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active') || !$this->getConfigData('function')) {
            $this->_logger->warning('Serverless Shipping is not configured properly.' .
                ' The module is disabled or the function is not set.');
            return false;
        }

        /**
         * @var $serverlessFunctionName string
         * @var $serverlessFunction ServerlessFunctionInterface
         */
        $serverlessFunctionName = $this->getConfigData('function');
        $serverlessFunction = $this->serverlessFunctionCollectionFactory->create()
            ->addFieldToFilter(ServerlessFunctionInterface::ID, $serverlessFunctionName)
            ->addFieldToFilter(ServerlessFunctionInterface::IS_ENABLED, 1)
            ->getFirstItem();

        if (!$serverlessFunction->getId()) {
            $this->_logger->warning('The serverless function ' . $serverlessFunctionName .
                ' is not enabled or does not exist.');
            return false;
        }

        // Decrypt cloud config from the serverless function
        $decryptedCloudConfig = $serverlessFunction->getCloudConfig(true);
        $serverlessFunction->setCloudConfig($decryptedCloudConfig, false);

        try {
            $requestData = json_decode(json_encode($request->getData()), true);
            
            /** @var CloudProviderInterface */
            $cloudProvider = $this->serverlessFunctionConfigRepository
                ->getCloudProviderByCode($serverlessFunction->getCloudProvider());
            $cloudProvider->execute($serverlessFunction, $requestData);

            if (is_array($requestData)) {
                /** @var Result $result */
                $result = $this->resultFactory->create();

                foreach ($requestData as $key => $value) {
                    /** @var Method $method */
                    $method = $this->methodFactory->create();

                    $returnedRate = new ShippingRate($value);

                    $method->setCarrier($this->_code);
                    $method->setCarrierTitle($this->getConfigData('title'));

                    $method->setMethod($this->_code . "_" . $returnedRate->getCode());
                    $method->setMethodTitle($returnedRate->getLabel());
                    $method->setPrice($returnedRate->getPrice());
                    $method->setCost($returnedRate->getCost());

                    $result->append($method);
                }

                return $result;
            }
        } catch (\Exception $e) {
            $this->_logger->error('Error while executing the serverless function ' .
                $serverlessFunctionName . ': ' . $e->getMessage());
            return false;
        }
    }
}
