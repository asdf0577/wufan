<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\ChatForm;

/**
 * GroupChatController
 *
 * @author
 *
 * @version
 *
 */
class GroupchatController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */
    protected $authservice;
    protected $storage;
   
    public function getAuthService()
	{
		if (! $this->authservice) {
			$this->authservice = $this->getServiceLocator()->get('AuthService()');
		}
	
		return $this->authservice;
	}
    protected function getLoggedInUser()
    {
        $userTable = $this->getServiceLocator()->get('Album\Model\UserTable');
        $userEmail = $this->getAuthService()->getStorage()->read();
        $user = $userTable->getUserByEmail($userEmail);
        
        return $user;
    }
    public function indexAction()
    {
        $user = $this->getLoggedInUser();
        $request = $this->getRequest();
        if($request->isPost()){
            $messageText = $request->getPost()->get('message');
            $fromUserId = $user->id;
            $this->sendMessage($messageText, $fromUserId);
            return $this->redirect()->toRoute('groupchat');
        }
        
        $form = new ChatForm();
        
        return new ViewModel( array(
        	'form' => $form,
            'username' => $user->name
        ));
    }
    
    public function messageListAction()
    {
        $sm = $this->getServiceLocator();
        $userTable = $sm->get('Album\Model\UserTable');
        $chatMessageTG = $sm->get('ChatMessageTableGateway');
        $chatMessages = $chatMessageTG->select();
        
        $messageList = array();
        foreach ($chatMessages as $chatmessage)
        {
            $fromUser = $userTable->getUser($chatmessage->user_id);
            $messageData = array();
            $messageData['user'] = $fromUser->name;
            $messageData['time'] = $chatmessage->stamp;
            $messageData['data'] = $chatmessage->message;
            $messageList[] = $messageData; 
            
        }
        $viewModel = new ViewModel(array(
        	'messageList' => $messageList
        ));
        $viewModel->setTemplate('album/groupchat/message-list');
        $viewModel->setTerminal(true);
        return $viewModel;
    }
    
    protected function sendMessage($messagetext,$fromUserId)
    {
        $chatMessageTg = $this->getServiceLocator()->get('ChatMessageTableGateway');
        
        $data = array
        (
        	'user_id' =>$fromUserId,
            'message' =>$messagetext,
            'stamp' =>Null
        );
        $chatMessageTg->insert($data);
        return true;
    }
}