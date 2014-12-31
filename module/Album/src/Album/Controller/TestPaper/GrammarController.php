<?php
namespace Album\Controller\TestPaper;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\form\GrammarForm;
use Album\Model\Grammar;
use Zend\Debug\Debug;
use Zend\XmlRpc\Value\String;
/**
 * GrammarController
 *
 * @author
 *
 * @version
 *
 */
class GrammarController extends AbstractActionController
{
    protected $GrammarTable;

    public function getGrammarTable()
    {
        if (!$this->GrammarTable) {
            $this->GrammarTable = $this->getServiceLocator()->get('GrammarTable');
            return $this->GrammarTable;
        }
    }
    
    public function indexAction()
    {
        // TODO Auto-generated GrammarController::indexAction() default action
        return new ViewModel();
    }
    
    public function addAction(){
        
        /* 通过下拉列表1选择考试科目后读取该科目下的题型 */
        $Grammars = $this->getGrammarTable()->getGrammars(0);
        $GrammarArray = array();
        // 将获取的题型从二维数组转一维数组
        foreach ($Grammars as $Type) {
            $conjure="";
            $conjure = $Type['name']."-".$Type['cn_name'];
            $GrammarArray[$Type['id']] = $conjure;
            $conjure="";
        }
        
        $form = new GrammarForm('GrammarForm');
        
        $form->get('grammarType')->setValueOptions($GrammarArray );
        $view = new ViewModel(array('form'=>$form,));
        $view->setTerminal(true);
        return $view;
    }
}