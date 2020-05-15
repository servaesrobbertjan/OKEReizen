<?php
class Zoekobject {

    private $bestemming;
    private $reistype;
 

    public function __construct($bestemming = null, $reistype=null)
    {
        $this->bestemming = $bestemming;
        $this->reistype = $reistype;
       
        
    }


 
    public function getBestemming()
    {
        return $this->bestemming;
    }

 
    public function setBestemming($bestemming)
    {
        $this->bestemming = $bestemming;

   
    }

  
    public function getReistype()
    {
        return $this->reistype;
    }


    public function setReistype($reistype)
    {
        $this->reistype = $reistype;

      
    }
}