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
namespace TIG\PostNL\Observer\SalesOrderSaveAfter;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use TIG\PostNL\Helper\Data;
use \TIG\PostNL\Model\OrderRepository;
use Magento\Sales\Model\Order as MagentoOrder;
use TIG\PostNL\Model\Order as PostNLOrder;

class CreatePostNLOrder implements ObserverInterface
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param OrderRepository $orderRepository
     * @param Data            $helper
     */
    public function __construct(
        OrderRepository $orderRepository,
        Data $helper
    ) {
        $this->orderRepository = $orderRepository;
        $this->helper = $helper;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var MagentoOrder $order */
        $magentoOrder = $observer->getData('data_object');

        if (!$this->helper->isPostNLOrder($magentoOrder)) {
            return;
        }

        $postnlOrder = $this->orderRepository->getByFieldWithValue('quote_id', $magentoOrder->getQuoteId());
        if (!$postnlOrder) {
            $postnlOrder = $this->orderRepository->create();
        }

        $postnlOrder->setData('order_id', $magentoOrder->getId());
        $postnlOrder->setData('quote_id', $magentoOrder->getQuoteId());

        $this->orderRepository->save($postnlOrder);
    }
}