<?php

require_once "Group.class.php";
require_once "Member.class.php";
//PHP Class for Profile

class Profile{
    public $name;
    public $image;
    public $description;
    public $profileID;
    public $groupID;

    function LoadDataFromDatabase($recID){
        $query = "select * from fumsocial.Profile  where  ProfileID=? ";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ($recID));
		if($rec=$res->fetch())
		{
			$this->profileID=$rec["ProfileID"];
			$this->groupID=$rec["GroupID"];
            $this->description=$rec["Description"];
            $this->name=$rec["Title"];
            $this->image=$rec["Picture"];
		}
    }
    
}

class ProfileManager{

    /**
	 * @return MaxID Integer ID of the last Profile created in the database.
	 */
    static function getLastID(){
		$mysql = pdodb::getInstance();
		$query = "select max(ProfileID) as MaxID from fumsocial.Profile";
		$res = $mysql->Execute($query);
		if($rec=$res->fetch())
		{
			return $rec["MaxID"];
		}
		return -1;
	}

    /**
	 * @param profileID Integer ID of the profile you are trying to set its GroupID field.
	 * @param groupID Integer ID of the group you want to add to the profile.
	 * @return state If not successful returns -1.
	 */
    public static function setGroupID($profileID, $groupID){
        $mysql = pdodb::getInstance();
		$query = "select GroupID from fumsocial.Profile where ProfileID=?";
        $mysql->Prepare($query);
        $res = $mysql->ExecuteStatement(array($profileID));
        $rec = $res->fetch();
        echo $rec["GroupID"];
        if ($rec["GroupID"]==NULL){
        $query = "update fumsocial.Profile set GroupID=? where ProfileID=?";
        $mysql->Prepare($query);
        $query_values = array();
        array_push($query_values, $groupID);
        array_push($query_values, $profileID);
        $mysql->ExecuteStatement($query_values);
        }
    }

    /**
	 * @param name String title of the profile.
	 * @param description String description of the profile.
	 * @param image Image of the profile.
     * @return lastID Integer ID of the created profile.
	 */
    public static function create($name, $description="",$image=""){
        $mysql = pdodb::getInstance();
        $query = "insert into fumsocial.Profile (Title";
        if($description!=""){
            $query .= ", Description";
        }
        if($image!=""){
            $query .= ", Picture";
        }
        $query .= ") values (?";
        if($description!=""){
            $query .= ", ?";
        }
        if($image!=""){
            $query .= ", ?";
        }
        $query .= ");";
        $valueList = array();
        array_push($valueList, $name);
        if($description!=""){
            array_push($valueList, $description);
        }
        if($image!=""){
            array_push($valueList, $image);
        }
        $mysql->Prepare($query);
        $mysql->ExecuteStatement($valueList);
        $lastID = ProfileManager::getLastID();
        return $lastID;
    }


    /**
     * @param groupID Integer ID of the group.
	 * @param name String title of the profile.	 
     * @return state If not successful returns -1.
	 */
    public static function updateName($groupID, $name){

        $mysql = pdodb::getInstance();
        
        $updaterID = $_SESSION["PersonID"];

        $query = "select Role from fumsocial.Group_member where GroupID=? AND UserID=?";
        $mysql->Prepare($query);
        $query_params = array();
        array_push($query_params, $groupID);
        array_push($query_params, $updaterID);
        $res = $mysql->ExecuteStatement($query_params);
        if($rec = $res->fetch()){
            if(Role::isMember($rec["Role"])){
                return -1;
            }
        }else{
            return -1;
        }

        $query = "update fumsocial.Profile set Title=? where GroupID=?";
		$ValueListArray = array();
		array_push($ValueListArray, $name);
		array_push($ValueListArray, $groupID); 
		$mysql->Prepare($query);
		$mysql->ExecuteStatement($ValueListArray);
        $mysql->audit("بروزرسانی نام پروفایل گروه با کد".$groupID);
    }


    /**
     * @param groupID Integer ID of the group.
	 * @param description String description of the profile.	 
     * @return state If not successful returns -1.
	 */
    public static function updateDescription($groupID, $description){
        
        $mysql = pdodb::getInstance();
        
        $updaterID = $_SESSION["PersonID"];

        $query = "select Role from fumsocial.Group_member where GroupID=? AND UserID=?";
        $mysql->Prepare($query);
        $query_params = array();
        array_push($query_params, $groupID);
        array_push($query_params, $updaterID);
        $res = $mysql->ExecuteStatement($query_params);
        if($rec = $res->fetch()){
            if(Role::isMember($rec["Role"])){
                return -1;
            }
        }else{
            return -1;
        }

        $query = "update fumsocial.Profile set Description=? where GroupID=?";
		$ValueListArray = array();
		array_push($ValueListArray, $description);
		array_push($ValueListArray, $groupID); 
		$mysql->Prepare($query);
		$mysql->ExecuteStatement($ValueListArray);
        $mysql->audit("بروزرسانی شرح پروفایل گروه با کد".$groupID);
    }


    /**
     * @param groupID Integer ID of the group to remove its description.
	 * @return state If not successful returns -1.
	 */
    public static function removeDescription($groupID){

        $mysql = pdodb::getInstance();
        
        $updaterID = $_SESSION["PersonID"];

        $query = "select Role from fumsocial.Group_member where GroupID=? AND UserID=?";
        $mysql->Prepare($query);
        $query_params = array();
        array_push($query_params, $groupID);
        array_push($query_params, $updaterID);
        $res = $mysql->ExecuteStatement($query_params);
        if($rec = $res->fetch()){
            if(Role::isMember($rec["Role"])){
                return -1;
            }
        }else{
            return -1;
        }

        $profileID = ManageGroup::getProfileID($groupID);
        $query = "update fumsocial.Profile set Description=NULL where ProfileID=?";
		$ValueListArray = array();
		array_push($ValueListArray, $profileID); 
		$mysql->Prepare($query);
		$mysql->ExecuteStatement($ValueListArray);
        $mysql->audit("حذف شرح پروفایل گروه با کد".$groupID);
    }


    /**
     * @param groupID Integer ID of the group to get its profile.
	 * @return profile profile of the group.
	 */
    public static function getProfileByGroupID($groupID){
        $mysql = pdodb::getInstance();
        $profileID = ManageGroup::getProfileID($groupID);
        $profile = new Profile();
        $profile->LoadDataFromDatabase($profileID);
        return $profile;
    }


    /**
     * @param groupID Integer ID of the group.
	 * @param image image of the profile.	 
     * @return state If not successful returns -1.
	 */
    public static function updateImageByGroupID($groupID, $image){

        $mysql = pdodb::getInstance();
        
        $updaterID = $_SESSION["PersonID"];

        $query = "select Role from fumsocial.Group_member where GroupID=? AND UserID=?";
        $mysql->Prepare($query);
        $query_params = array();
        array_push($query_params, $groupID);
        array_push($query_params, $updaterID);
        $res = $mysql->ExecuteStatement($query_params);
        if($rec = $res->fetch()){
            if(Role::isMember($rec["Role"])){
                return -1;
            }
        }else{
            return -1;
        }

        $profileID = ManageGroup::getProfileID($groupID);
        $query = "update fumsocial.Profile set Picture=? where ProfileID=?";
		$ValueListArray = array();
		array_push($ValueListArray, $image);
		array_push($ValueListArray, $profileID); 
		$mysql->Prepare($query);
		$mysql->ExecuteStatement($ValueListArray);
        $mysql->audit("بروزرسانی تصویر پروفایل گروه با کد".$groupID);
    }


    /**
     * @param groupID Integer ID of the group to remove its image.
     * @return state If not successful returns -1.
	 */
    public static function removeImageByGroupID($groupID){

        $mysql = pdodb::getInstance();
        
        $updaterID = $_SESSION["PersonID"];

        $query = "select Role from fumsocial.Group_member where GroupID=? AND UserID=?";
        $mysql->Prepare($query);
        $query_params = array();
        array_push($query_params, $groupID);
        array_push($query_params, $updaterID);
        $res = $mysql->ExecuteStatement($query_params);
        if($rec = $res->fetch()){
            if(Role::isMember($rec["Role"])){
                return -1;
            }
        }else{
            return -1;
        }

        $profileID = ManageGroup::getProfileID($groupID);
        $query = "update fumsocial.Profile set Picture=NULL where ProfileID=?";
		$ValueListArray = array();
		array_push($ValueListArray, $profileID); 
		$mysql->Prepare($query);
		$mysql->ExecuteStatement($ValueListArray);
        $mysql->audit("حذف تصویر پروفایل گروه با کد".$groupID);
    }


    /**
     * @param groupID Integer ID of the group to get its profile.
     * @return image image of the group profile.
	 */
    public static function getImageByGroupID($groupID){
        $mysql = pdodb::getInstance();
        $profileID = ManageGroup::getProfileID($groupID);
        $query = "select Picture from fumsocial.Profile where ProfileID=?";
		$ValueListArray = array();
		array_push($ValueListArray, $profileID); 
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement($ValueListArray);
        if($rec=$res->fetch())
		{
			return $rec["Picture"];
		}
		return -1;
    }

}

?>