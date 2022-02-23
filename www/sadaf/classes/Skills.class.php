<?php
class be_skills {
  public $SkillID;
  public $SkillName;

  function be_skills() {}
}
class manage_skills {
  static function GetLastID(){
		$mysql = pdodb::getInstance();
		$query = "select max(SkillID) as MaxID from sadaf.Skills";
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
    $skills = array();
    $query = "select Skills.* from sadaf.Skills "." limit ".$FromRec.",".$NumberOfRec." ";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ());
    $i = 0;
		while($rec = $res->fetch())
		{
      $skill = new be_skills();
			$skill->SkillID=$rec["SkillID"];
			$skill->SkillName=$rec["SkillName"];
      $skills[$i] = $skill;
      $i++;
		}
    return $skills;
  }
  static function getByName($SkillName) {
    $query = "select Skills.* from sadaf.Skills where SkillName = ?";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ($SkillName));
		if($rec = $res->fetch())
		{
      $skill = new be_skills();
			$skill->SkillID=$rec["SkillID"];
			$skill->SkillName=$rec["SkillName"];
      return $skill;
		}
    return null;
  }
  static function GetPersonSkills($PersonID) {
    $skills = array();
    $query = "select Skills.* from sadaf.Skills join PersonsSkills on PersonsSkills.SkillID = Skills.SkillID and PersonsSkills.PersonID = ?";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ($PersonID));
    $i = 0;
		while($rec = $res->fetch())
		{
      $skill = new be_skills();
			$skill->SkillID=$rec["SkillID"];
			$skill->SkillName=$rec["SkillName"];
      $skills[$i] = $skill;
      $i++;
		}
    return $skills;
  }
  static function PersonHasSkill($PersonID, $SkillID) {
    $mysql = pdodb::getInstance();
		$query = "select SkillID from sadaf.PersonsSkills where PersonID = ? and SkillID = ?";
    $mysql->Prepare ($query);
    $res = $mysql->ExecuteStatement (array ($PersonID,$SkillID));
		if($rec=$res->fetch())
		{
			return true;
		}
		return false;
  }
  static function Add($name) {
    $query = "insert into sadaf.Skills (SkillName) values (?)";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$mysql->ExecuteStatement (array ($name));
    $LastID = manage_skills::GetLastID();
    return $LastID;
  }
  static function AddToPerson($SkillID, $PersonID) {
    $query = "insert into sadaf.PersonsSkills (SkillID, PersonID) values (?, ?)";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$mysql->ExecuteStatement (array ($SkillID, $PersonID));
    $LastID = manage_skills::GetLastID();
    return $LastID;
  }
  static function RemoveFromPerson($SkillID, $PersonID) {
    $query = "delete from sadaf.PersonsSkills where SkillID = ? and  PersonID = ?";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$mysql->ExecuteStatement (array ($SkillID, $PersonID));
  }
}
?>