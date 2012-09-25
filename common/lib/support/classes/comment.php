<?php

class comment
{
    private $description;
    private $creationdate;
    private $creatorname;
    private $viewed_cust;
    private $viewed_agent;
    private $viewed_admin;

    public function __construct($id, $desc, $date, $viewed_cust, $viewed_agent, $viewed_admin)
    {
        $this->id = $id;
        $this->description = $desc;
        $this->creationdate = $date;
        $this->viewed_cust = $viewed_cust;
        $this->viewed_agent = $viewed_agent;
        $this->viewed_admin = $viewed_admin;

    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    //0 customer
    //1 agent
    //2 admin
    public function getViewed($type)
    {
        switch ($type) {
            case 0 :
                return $this->viewed_cust;
            case 1 :
                return $this->viewed_agent;
            case 2 :
                return $this->viewed_admin;
            default :
                return 0;
        }
    }

    public function getCreationdate()
    {
        return substr($this->creationdate, 0, 19);
    }

    public function setCreatorname($creatorname)
    {
        $this->creatorname = $creatorname;
    }

    public function getCreatorname()
    {
        return $this->creatorname;
    }

}
