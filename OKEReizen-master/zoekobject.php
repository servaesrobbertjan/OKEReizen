<?php
class Zoekobject {

    private $bestemmingsid;
    private $reistype;
 

    public function __construct($bestemmingsid = null, $reistype=null)
    {
        $this->bestemmingsid = $bestemmingsid;
        $this->reistype = $reistype;
       
        
    }


 
    public function getBestemmingsId()
    {
        return $this->bestemmingsid;
    }

 
    public function setBestemmingsId($bestemmingsid)
    {
        $this->bestemmingsid = $bestemmingsid;

   
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