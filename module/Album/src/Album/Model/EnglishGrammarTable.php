<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
class EnglishGrammarTable{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getByStudent($sid){
      $result = $this->tableGateway->select(array('sid'=>$sid))->toCurrent();
        return $result;
    }
   
    public function saveEnglishGrammarData($StudentData)
    {
        $data = array(
            'sid'=>$studentData->sid,
            'cid'=>$studentData->cid,
            'Adjectives'=>$studentData->Adjectives,
            'Adverbs'=>$studentData->Adverbs,
            'Articles'=>$studentData->Articles,
            'Clauses'=>$studentData->Clauses,
            'Compounds'=>$studentData->Compounds,
            'Conditionals'=>$studentData->Conditionals,
            'Conjunctions'=>$studentData->Conjunctions,
            'Determiners'=>$studentData->Determiners,
            'Gender'=>$studentData->Gender,
            'Idiom'=>$studentData->Idiom,
            'Interjections'=>$studentData->Interjections,
            'Inversion'=>$studentData->Inversion,
            'Nouns'=>$studentData->Nouns,
            'Pronouns'=>$studentData->Pronouns,
            'Phrases'=>$studentData->Phrases,
            'Plurals'=>$studentData->Plurals,
            'Possessives'=>$studentData->Possessives,
            'Prepositions'=>$studentData->Prepositions,
            'Verbs'=>$studentData->Verbs,
            'time'=>time(),
            
        );
        $id = (int)$studentData->id;
        if($id == 0)
        {
            $this->tableGateway->insert($data);  
        }
    }
    public function delete($id){
    	$id = (int)$id;
    	$this->tableGateway->delete(array('id'=>$id));
    }
   
}