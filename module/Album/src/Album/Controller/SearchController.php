<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;


/**
 * SearchController
 *
 * @author
 *
 * @version
 *
 */
class SearchController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated SearchController::indexAction() default action
        return new ViewModel();
    }
    
    public function getIndexLocation()
    {
        $config = $this->getServiceLocator()->get('config');
        if ($config instanceof \Traversable)
        {
           $config = ArrayUtils::iteratorToArray($config);
        }
        if(!empty($config['module_config']['search_index']))
        {
            return $config['module_config']['search_index'];
        }else{
            return FALSE;
        }
    }
    
    public function generateindexAction()
    {
        $searchIndexLocation = $this->getIndexLocation();
     $index = lucene\Lucene::create($searchIndexLocation); 
        
        $userTable = $this->getServiceLocator()->get('Album\Model\UserTable');
        $uploadTable = $this->getServiceLocator()->get('Album\Model\UploadTable');
        $allUploas = $uploadTable->fetchAll();
        
        foreach ($allUploas as $fileUpload)
        {
            $uploadOwner = $userTable->getUser($fileUpload->user_id);
            
            $fileUploadId = Document\Field::unIndexed('upload_id', $fileUpload->id);
            $label = Document\Field::text('label', $fileUpload->label);
            $owenr = Document\Field::text('owner', $uploadOwner->name);
            
            $indexDoc = new Lucene\Document(); 
            $indexDoc->addField($fileUploadId);
            $indexDoc->addField($label);
            $indexDoc->addField($owenr);
            
            $index->addDocument($indexDoc);
        }
    }
}