<?php
class be_projects {
  public $ProjectID;
  public $ProjectName;
  public $StartDate;
  public $EndDate;
  public $Description;
  public $Link;

  function be_projects() {}
}
class be_persons {
  public $PersonID;
  public $pfname;
  public $plname;
  public $CardNumber;

  function be_persons() {}
}
class manage_projects {
  static function GetLastID(){
		$mysql = pdodb::getInstance();
		$query = "select max(ProjectID) as MaxID from sadaf.Projects";
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
    $projects = array();
    $query = "select Projects.* from sadaf.Projects "." limit ".$FromRec.",".$NumberOfRec." ";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ());
    $i = 0;
		while($rec = $res->fetch())
		{
      $project = new be_projects();
			$project->ProjectID=$rec["ProjectID"];
			$project->ProjectName=$rec["ProjectName"];
      $projects[$i] = $project;
      $i++;
		}
    return $projects;
  }
  static function getByName($ProjectName) {
    $query = "select Projects.* from sadaf.Projects where ProjectName = ?";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ($ProjectName));
		if($rec = $res->fetch())
		{
      $project = new be_projects();
			$project->ProjectID=$rec["ProjectID"];
			$project->ProjectName=$rec["ProjectName"];
      return $project;
		}
    return null;
  }
  static function GetPersonProjects($PersonID) {
    $projects = array();
    $query = "select Projects.* from sadaf.Projects join PersonsProjects on PersonsProjects.ProjectID = Projects.ProjectID and PersonsProjects.PersonID = ?";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ($PersonID));
    $i = 0;
		while($rec = $res->fetch())
		{
      $project = new be_projects();
			$project->ProjectID=$rec["ProjectID"];
			$project->ProjectName=$rec["ProjectName"];
      $project->StartDate=$rec["StartDate"];
      $project->EndDate=$rec["EndDate"];
      $project->Description=$rec["Description"];
      $project->Link=$rec["Link"];
      $projects[$i] = $project;
      $i++;
		}
    return $projects;
  }
  static function PersonHasProject($PersonID, $ProjectID) {
    $mysql = pdodb::getInstance();
		$query = "select ProjectID from sadaf.PersonsProjects where PersonID = ? and ProjectID = ?";
    $mysql->Prepare ($query);
    $res = $mysql->ExecuteStatement (array ($PersonID,$ProjectID));
		if($rec=$res->fetch())
		{
			return true;
		}
		return false;
  }
  static function Add($name, $StartDate, $EndDate, $Description, $Link) {
    $query = "insert into sadaf.Projects (ProjectName, StartDate, EndDate, Description, Link) values (?,?,?,?,?)";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$mysql->ExecuteStatement (array ($name, $StartDate, $EndDate, $Description, $Link));
    $LastID = manage_projects::GetLastID();
    return $LastID;
  }
  static function AddToPerson($ProjectID, $PersonID) {
    $query = "insert into sadaf.PersonsProjects (ProjectID, PersonID) values (?, ?)";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$mysql->ExecuteStatement (array ($ProjectID, $PersonID));
    $LastID = manage_projects::GetLastID();
    return $LastID;
  }
  static function RemoveFromPerson($ProjectID, $PersonID) {
    $query = "delete from sadaf.PersonsProjects where ProjectID = ? and  PersonID = ?";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$mysql->ExecuteStatement (array ($ProjectID, $PersonID));
  }
  static function GetPersons() {
    $persons = array();
    $query = "select Persons.* from sadaf.Persons";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ());
    $i = 0;
		while($rec = $res->fetch())
		{
      $person = new be_persons();
			$person->PersonID=$rec["PersonID"];
			$person->pfname=$rec["pfname"];
      $person->plname=$rec["plname"];
      $persons[$i] = $person;
      $i++;
		}
    return $persons;
  }
}
?>