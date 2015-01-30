<?php
namespace Album\Model;
class EnglishGrammar {
    public  $id;
    public  $cid;
    public  $Adverbs;
    public  $Articles;
    public  $Clauses;
    public  $Compounds;
    public  $Conditionals;
    public  $Conjunctions;
    public  $Determiners;
    public  $Gender;
    public  $Idiom;
    public  $Interjections;
    public  $Inversion;
    public  $Nouns;
    public  $Pronouns;
    public  $Phrases;
    public  $Plurals;
    public  $Possessives;
    public  $Prepositions;
    public  $Verbs; 
    public  $time; 
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->cid = (isset($data['cid'])) ? $data['cid'] : null;
        $this->Adjectives  = (isset($data[＇Adjectives＇]))?$data[＇Adjectives＇]:null;
        $this->Adverbs  = (isset($data['Adverbs']))?$data['Adverbs']:null;
        $this->Articles  = (isset($data['Articles']))?$data['Articles']:null;
        $this->Clauses  = (isset($data['Clauses']))?$data['Clauses']:null;
        $this->Compounds  = (isset($data['Compounds']))?$data['Compounds']:null;
        $this->Conditionals  = (isset($data['Conditionals']))?$data['Conditionals']:null;
        $this->Conjunctions  = (isset($data['Conjunctions']))?$data['Conjunctions']:null;
        $this->Determiners  = (isset($data['Determiners']))?$data['Determiners']:null;
        $this->Gender  = (isset($data['Gender']))?$data['Gender']:null;
        $this->Idiom  = (isset($data['Idiom']))?$data['Idiom']:null;
        $this->Interjections  = (isset($data['Interjections']))?$data['Interjections']:null;
        $this->Inversion  = (isset($data['Inversion']))?$data['Inversion']:null;
        $this->Nouns  = (isset($data['Nouns']))?$data['Nouns']:null;
        $this->Pronouns  = (isset($data['Pronouns']))?$data['Pronouns']:null;
        $this->Phrases  = (isset($data['Phrases']))?$data['Phrases']:null;
        $this->Plurals  = (isset($data['Plurals']))?$data['Plurals']:null;
        $this->Possessives  = (isset($data['Possessives']))?$data['Possessives']:null;
        $this->Prepositions  = (isset($data['Prepositions']))?$data['Prepositions']:null;
        $this->Verbs  = (isset($data['Verbs']))?$data['Verbs']:null;
        
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}