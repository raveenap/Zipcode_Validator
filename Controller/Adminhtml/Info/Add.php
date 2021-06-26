<?php

namespace   Codilar\Zipcode\Controller\Adminhtml\Info;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Add
 * @license  Open Source
 * @link     https://www.codilar.com
 * @copyright Copyright © 2021 Codilar Technologies Pvt. Ltd.. All rights reserved
 *
 */
class Add extends Action
{

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultResponse = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultResponse->getConfig()->getTitle()->set(__("Add new region"));
        return $resultResponse;
    }
}
