<?php

class invoiceItem
{
    private $description;
    private $date;
    private $price;
    private $VAT;
    private $precision = false;
    private $ext_id;
    private $ext_type;

    public function __construct($id, $desc, $date, $price, $VAT,$type_ext,$id_ext=null)
    {
        $this->id = $id;
        $this->description = $desc;
        $this->date = $date;
        $this->price = $price;
        $this->VAT = $VAT;
        $this->ext_id = $id_ext;
        $this->ext_type = $type_ext;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExtId()
    {
        return $this->ext_id;
    }
    public function getExtType()
    {
        return $this->ext_type;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getVAT()
    {
        return $this->VAT;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDate()
    {
        return substr($this->date, 0, 10);
    }

}
