<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\PostNL\Service\Timeframe;

use TIG\PostNL\Config\Provider\ShippingOptions;
use TIG\PostNL\Helper\AddressEnhancer;

class Options
{
    const DAYTIME_DELIVERY_OPTION = 'Daytime';
    const EVENING_DELIVERY_OPTION = 'Evening';
    const SUNDAY_DELIVERY_OPTION  = 'Sunday';

    /**
     * @var ShippingOptions
     */
    private $shippingOptions;

    /**
     * @var AddressEnhancer
     */
    private $addressEnhancer;

    /**
     * @param ShippingOptions $shippingOptions
     * @param AddressEnhancer $addressEnhancer
     */
    public function __construct(
        ShippingOptions $shippingOptions,
        AddressEnhancer $addressEnhancer
    ) {
        $this->shippingOptions = $shippingOptions;
        $this->addressEnhancer = $addressEnhancer;
    }

    /**
     * @param string $countryId
     *
     * @return array
     */
    public function get($countryId = 'NL')
    {
        $deliveryTimeframesOptions = [self::DAYTIME_DELIVERY_OPTION];

        if ($this->shippingOptions->isEveningDeliveryActive($countryId)) {
            $deliveryTimeframesOptions[] = self::EVENING_DELIVERY_OPTION;
        }

        if ($this->shippingOptions->isSundayDeliveryActive()) {
            $deliveryTimeframesOptions[] = self::SUNDAY_DELIVERY_OPTION;
        }

        return $deliveryTimeframesOptions;
    }
}
