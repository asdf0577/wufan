<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\form\KnowledgeForm;
use Album\Model\Knowledge;
use Zend\Debug\Debug;
use Zend\XmlRpc\Value\String;
/**
 * KnowledgeController
 *
 * @author
 *
 * @version
 *
 */
class KnowledgeController extends AbstractActionController
{
protected $KnowledgeTable; 
    
    public function getKnowledgeTable()
    {
        if (!$this->KnowledgeTable) {
            $this->KnowledgeTable = $this->getServiceLocator()->get('KnowledgeTable');
            return $this->KnowledgeTable;
        }
    }
    
    public function indexAction()
    {
        // TODO Auto-generated GrammarController::indexAction() default action
        return new ViewModel();
    }
    
    public function addAction(){
        
        /* 通过下拉列表1选择考试科目后读取该科目下的题型 */
        $Knowledges = $this->getKnowledgeTable()->getKnowledges(0);
        $KnowledgeArray = array();
        // 将获取的题型从二维数组转一维数组
        foreach ($Knowledges as $Type) {
            $conjure="";
            $conjure = $Type['name']."-".$Type['cn_name'];
            $KnowledgeArray[$Type['id']] = $conjure;
            $conjure="";
        }
        
        $form = new KnowledgeForm('KnowledgeForm');
        
        $form->get('knowledgeType')->setValueOptions($KnowledgeArray );
        $view = new ViewModel(array('form'=>$form,));
        $view->setTerminal(true);
        return $view;
    }
}