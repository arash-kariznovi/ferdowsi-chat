<?php
require_once "Profile.class.php";
require_once "Member.class.php";

//PHP Class for Group

class Group{
    public $profileID;
    public $groupID;
	public $ownerID;

    function LoadDataFromDatabase($recID){
        $query = "select * from fumsocial.Group  where  GroupID=? ";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ($RecID));
		if($rec=$res->fetch())
		{
			$this->profileID=$rec["ProfileID"];
			$this->groupID=$rec["GroupID"];
			$this->ownerID=$rec["OwnerID"];
		}
    }
}

class ManageGroup{


	/**
	 * @return MaxID Integer ID of the last Group created in the database.
	 */
	static function getLastID(){
		$mysql = pdodb::getInstance();
		$query = "select max(GroupID) as MaxID from fumsocial.Group";
		$res = $mysql->Execute($query);
		if($rec=$res->fetch())
		{
			return $rec["MaxID"];
		}
		return -1;
	}
	/**
	 * @param name string name of the group.
	 * @param description description string for the group.
	 * @param image image file for the group.
	 * @return lastID Integer ID of the group created. If not successful returns -1.
	 */
	public static function create($name, $description="", $image="", $ownerID=0){
		
		$mysql = pdodb::getInstance();

		if($ownerID==0){
			$ownerID = $_SESSION['PersonID'];
		}else{	
			$query = "select * from sadaf.accountspecs where AccountSpecID=?";
			$mysql->Prepare($query);
			$res = $mysql->ExecuteStatement(array($ownerID));
			if(!($ownerID = $res->fetch())){
				return -1;
			}
		}				

		$profileID = ProfileManager::create($name, $description, $image);
		
		$query = "insert into fumsocial.Group (ProfileID, OwnerID) values (?, ?);";
		$mysql->Prepare($query);
		$query_params = array();
		array_push($query_params, $profileID);
		array_push($query_params, $ownerID);
		$mysql->ExecuteStatement($query_params);
		
		$lastID = ManageGroup::getLastID();
		ProfileManager::setGroupID($profileID, $lastID);
		ManageMember::add($lastID, $ownerID, Role::Owner);
		
		$mysql->audit("ایجاد گروه جدید با کد ".$lastID); 

		return $lastID;
	}
	/**
	 * @param groupID Integer ID of the group.
	 * @return ProfileID Integer ID of the group's profile. If not successful returns -1.
	 */
	public static function getProfileID($groupID){
		$mysql = pdodb::getInstance();
		$query = "select ProfileID from fumsocial.Group where GroupID=?";
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement(array($groupID));
		if($rec=$res->fetch())
		{
			return $rec["ProfileID"];
		}
		return -1;
	}


	/**
	 * @param groupID Integer ID of the group.
	 * @return state If successful returns 1. If not successful returns -1.
	 */
	public static function remove($groupID){
		$mysql = pdodb::getInstance();
		$userID = $_SESSION['PersonID'];
		$query = "select OwnerID from fumsocial.Group where GroupID=?";
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement(array($groupID));

		if($rec = $res->fetch()){
			if($userID != $rec["OwnerID"]){
				return -1;
			}
		}

		$query = "delete from fumsocial.Group where GroupID = ?";
		$mysql->Prepare($query);
		$mysql->ExecuteStatement(array($groupID));
		$mysql->audit("حذف گروه با کد ".$groupID);
		return 1;
	}


	public static function autoLessonGroupCreate(){

		$mysql = pdodb::getInstance();		

		//get all the students that has selected a course 
		$query = "select LesID, PersonID from educ.SelectedCourses left join educ.StudentSpecs on
		educ.SelectedCourses.StNo = educ.StudentSpecs.StNo AND educ.SelectedCourses.CourseStatus='ADD';";
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement();
		$studentList = array();
		while($rec = $res->fetch()){
			array_push($studentList ,$rec);
		}

		//geting names of each lesson
		$query = "select LesID, PLesName from educ.PresentedLessons left join educ.lessons on 
		educ.PresentedLessons.LesCode = educ.lessons.LesCode";
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement();
		$pLesNameDict = array();
		while($rec = $res->fetch()){
			$pLesNameDict[$rec["LesID"]] = $rec["PLesName"];
		}

		//getting professor's name and ID
		$query = "select LesID, PersonID, PProLName from educ.PresentedLessons left join educ.professors on 
		educ.PresentedLessons.ProCode = educ.professors.ProCode";
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement();
		$lesProfDict = array();
		while($rec = $res->fetch()){
			$tmp = array($rec["PersonID"], $rec["PProName"]);
			$lesProfDict[$rec["LesID"]] = $tmp;
		}

		$query = "select distinct LesID from educ.SelectedCourses;";
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement();

		while($rec = $res->fetch()){
			$lessonName = "نام درس";
			$teacherID = 0;
			$teacherName = "نام استاد";
			$groupTitle = strval($rec["LesID"]);
			//geting lesson properties.
			if(array_key_exists($rec["LesID"], $pLesNameDict)){
				$lessonName = $pLesNameDict[$rec["LesID"]];
				$groupTitle = $lessonName;
			}
			if(array_key_exists($rec["LesID"], $lesProfDict)){				
				$teacherID = $lesProfDict[$rec["LesID"]][0];
				$teacherName = $lesProfDict[$rec["LesID"]][1];
			}
			$groupTitle .= " " . $teacherName;

			$description = "گروه درسی " . $lessonName . " استاد " . $teacherName;
			$groupID = ManageGroup::create($groupTitle, $description, NULL, $teacherID);

			$memberList = array();
			foreach($studentList as $row){
				if($row["LesID"] == $rec["LesID"]){
					array_push($memberList, $row["PersonID"]);
				}
			}
			ManageMember::addMembers($groupID, $memberList);
		}
	}

}

?>