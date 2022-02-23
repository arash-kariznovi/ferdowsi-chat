<?php
class be_Interests {
    public $InterestID;
    public $InterestName;

    function be_Interests() {}
}
class manage_Interests {
    static function GetLastID(){
        $mysql = pdodb::getInstance();
        $query = "select max(InterestID) as MaxID from sadaf.Interests";
        $res = $mysql->Execute($query);
        if($rec=$res->fetch())
        {
            return $rec["MaxID"];
        }
        return -1;
    }
    static function GetList($FromRec, $NumberOfRec) {
        if(!is_numeric($FromRec))
            $FromRec=0;
        if(!is_numeric($NumberOfRec))
            $NumberOfRec=0;
        $interests = array();
        $query = "select Interests.* from sadaf.Interests "." limit ".$FromRec.",".$NumberOfRec." ";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($query);
        $res = $mysql->ExecuteStatement (array ());
        $i = 0;
        while($rec = $res->fetch())
        {
            $interest = new be_Interests();
            $interest->InterestID=$rec["InterestID"];
            $interest->InterestName=$rec["InterestName"];
            $interests[$i] = $interest;
            $i++;
        }
        return $interests;
    }
    static function getByName($InterestName) {
        $query = "select Interests.* from sadaf.Interests where InterestName = ?";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($query);
        $res = $mysql->ExecuteStatement (array ($InterestName));
        if($rec = $res->fetch())
        {
            $interest = new be_Interests();
            $interest->InterestID=$rec["InterestID"];
            $interest->InterestName=$rec["InterestName"];
            return $interest;
        }
        return null;
    }
    static function GetPersonInterests($PersonID) {
        $interests = array();
        $query = "select Interests.* from sadaf.Interests join PersonsInterests on PersonsInterests.InterestID = Interests.InterestID and PersonsInterests.PersonID = ?";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($query);
        $res = $mysql->ExecuteStatement (array ($PersonID));
        $i = 0;
        while($rec = $res->fetch())
        {
            $interest = new be_Interests();
            $interest->InterestID=$rec["InterestID"];
            $interest->InterestName=$rec["InterestName"];
            $interests[$i] = $interest;
            $i++;
        }
        return $interests;
    }
    static function PersonHasInterest($PersonID, $InterestID) {
        $mysql = pdodb::getInstance();
        $query = "select InterestID from sadaf.PersonsInterests where PersonID = ? and InterestID = ?";
        $mysql->Prepare ($query);
        $res = $mysql->ExecuteStatement (array ($PersonID,$InterestID));
        if($rec=$res->fetch())
        {
            return true;
        }
        return false;
    }
    static function Add($name) {
        $query = "insert into sadaf.Interests (InterestName) values (?)";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($query);
        $mysql->ExecuteStatement (array ($name));
        $LastID = manage_Interests::GetLastID();
        return $LastID;
    }
    static function AddToPerson($InterestID, $PersonID) {
        $query = "insert into sadaf.PersonsInterests (InterestID, PersonID) values (?, ?)";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($query);
        $mysql->ExecuteStatement (array ($InterestID, $PersonID));
        $LastID = manage_Interests::GetLastID();
        return $LastID;
    }

    static function RemoveFromPerson($InterestID, $PersonID) {
        $query = "delete from sadaf.PersonsInterests where InterestID = ? and  PersonID = ?";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($query);
        $mysql->ExecuteStatement (array ($InterestID, $PersonID));
    }

    static function GetRecommendedPersons($PersonID) {
        $persons = array();
        $sql = "select * from sadaf.persons where sadaf.persons.PersonID in (SELECT  distinct sadaf.persons.PersonID FROM sadaf.persons join sadaf.PersonsInterests on persons.PersonID WHERE PersonsInterests.PersonID = sadaf.persons.PersonID and not(sadaf.persons.PersonID = $PersonID)  and  PersonsInterests.InterestID in (select PersonsInterests.InterestID from sadaf.PersonsInterests where PersonsInterests.PersonID = ?) )";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($sql);
        $res1 = $mysql->ExecuteStatement (array ($PersonID));
        $j = 0;
        while($rec = $res1->fetch())
        {
            $person="" ;
            $person->pfname=$rec["pfname"];
            $person->plname=$rec["plname"];
            $persons[$j] = $person;
            $j++;
        }
        return $persons;


    }
}
?>