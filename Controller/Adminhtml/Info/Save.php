<?php

namespace Codilar\Zipcode\Controller\Adminhtml\Info;

use Codilar\Zipcode\Api\ZipcodeRepositoryInterface;
use Codilar\Zipcode\Api\ZipcodesRepositoryInterface;
use Codilar\Zipcode\Model\Zipcode\FileUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\Model\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\File\Csv;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Save
 *
 * @license  Open Source
 * @link     https://www.codilar.com
 * @copyright Copyright © 2021 Codilar Technologies Pvt. Ltd.. All rights reserved
 *
 * A magento 2 module to have zipcode for products
 */
class Save implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var ZipcodeRepositoryInterface
     */
    private $zipcodeRepository;
    /**
     * @var ZipcodesRepositoryInterface
     */
    private $zipcodesRepository;
    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Codilar_Zipcode::zipcode_read';
    /**
     * @var
     */
    private $csvUploader;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * Save constructor.
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param Csv $csvProcessor
     * @param ResultFactory $resultFactory
     * @param RequestInterface $request
     * @param ZipcodeRepositoryInterface $zipcodeRepository
     * @param ZipcodesRepositoryInterface $zipcodesRepository
     * @param FileUploader $fileUploader
     * @param ManagerInterface $manager
     * @param UrlInterface $url
     * @param Csv $csv
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        Csv $csvProcessor,
        ResultFactory $resultFactory,
        RequestInterface $request,
        ZipcodeRepositoryInterface $zipcodeRepository,
        ZipcodesRepositoryInterface $zipcodesRepository,
        FileUploader $fileUploader,
        ManagerInterface $manager,
        UrlInterface $url
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->url = $url;
        $this->zipcodeRepository = $zipcodeRepository;
        $this->manager = $manager;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->csvProcessor = $csvProcessor;
        $this->zipcodesRepository = $zipcodesRepository;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $redirectResponse = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirectResponse->setUrl($this->url->getUrl('*/*/index'));
        try{
            $model = $this->zipcodeRepository->load($this->request->getParam('id'));
            $model->setData($this->request->getParams());
            $this->zipcodeRepository->save($model);
            $collection=$this->zipcodeRepository->getCollection()->getLastItem();
            $id=$collection->getId();
            $this->manager->addSuccessMessage(
                __(sprintf(
                        'The Region %s Information has been saved Successfully',
                        $this->request->getParam('region')
                    )
                )
            );
        } catch (\Exception $exception) {
            $this->manager->addErrorMessage(
                __(sprintf(
                        'The Region %s Information has not been saved due to Some Technical Reason',
                        $this->request->getParam('region')
                    )
                )
            );
        }
        $data = $this->request->getParams();
        try {
            if(isset($data['zipcode']['0'])) {
                $data['zipcode'] = $data['zipcode']['0']['name'];
            }
            else {
                $data['zipcode'] = null;
            }
            if(isset($data['zipcode']) && !is_null($data['zipcode']))
            {
                $this->fileUploader->moveFileFromTmp($data['zipcode']);
                $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath()
                    . 'Codilar/zipcode/' . $data['zipcode'];
                $importProductRawData = $this->csvProcessor->getData($mediaPath);
                $count = 0;

                if(isset($id)&& $id){
                    foreach ($importProductRawData as $rowIndex => $dataRow)
                    {
                        if($rowIndex > 0)
                        {
                            $model = $this->zipcodesRepository->load(null)
                                ->setData('zipcode', $dataRow[1])
                                ->setData('time_deliver', $dataRow[2])
                                ->setData('region_id', $id);
                            $this->zipcodesRepository->save($model);

                            $count++;
                        }
                    }
                }
                $this->manager->addSuccessMessage(__('Total %1 pincodes added / updated successfully.', $count));
            }
            else
                $this->manager->addErrorMessage(__('CSV file not uploaded properly, please try again!'));
        } catch (\Exception $e) {
            $this->manager->addErrorMessage($e->getMessage());
        }
        return $redirectResponse;
    }
}
