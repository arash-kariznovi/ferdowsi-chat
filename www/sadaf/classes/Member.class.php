<?php

//PHP Class for Member

class Role{
    public const Owner = "Owner";
    public const Admin = "Admin";
    public const Member = "Member";

    public static function isValid($role){
        if ($role != Role::Owner && $role != Role::Admin && $role != Role::Member){
            return FALSE;
        }
        return TRUE;
    }

    public static function isOwner($role){
        return $role == Role::Owner;
    }
    public static function isAdmin($role){
        return $role == Role::Admin;
    }
    public static function isMember($role){
        return $role == Role::Member;
    }

    public static function toFarsi($role){
        if($role == Role::Owner){
            return "سازنده گروه";
        }elseif($role == Role::Admin){
            return "مدیر گروه";
        }elseif($role == Role::Member){
            return "عضو";
        }
    }
}

class Member{
    public $memberID;
    public $groupID;
    public $userID;
    public $role;
    public $joinedAt;
    public $addedBy;
    public $fname;
    public $lname;

    function LoadDataFromDatabase($recID){
        $query = "select * from fumsocial.Group_member  where  MemberID=? ";
		$mysql = pdodb::getInstance();
		$mysql->Prepare ($query);
		$res = $mysql->ExecuteStatement (array ($RecID));
		if($rec=$res->fetch())
		{
			$this->memberID=$rec["MemberID"];
			$this->groupID=$rec["GroupID"];
            $this->userID=$rec["UserID"];
            $this->role=$rec["Role"];
            $this->joinedAt=$rec["GroupID"];
            $this->addedBy=$rec["AddedBy"];
		}
    }
}

class ManageMember{

    /**
	 * @return MaxID Integer ID of the last Member created in the database.
	 */
	public static function getLastID(){
		$mysql = pdodb::getInstance();
		$query = "select max(MemberID) as MaxID from fumsocial.Group_member";
		$res = $mysql->Execute($query);
		if($rec=$res->fetch())
		{
			return $rec["MaxID"];
		}
		return -1;
	}
    
    /**
	 * @param groupID Integer ID of the group you want to add member to.
	 * @param userID Integer ID of the member whose going to be added.
	 * @param role Role of the new member. Role has 3 valid values:
     * Owner - is the role of the owner of the group. only one owner is allowed.
     * Admin - is the role of the admins of the group. multiple admins are allowed in a group.
     * Member - defualt role of every member of the group. A group can have multiple members.
	 * @return lastID Integer ID of the member added to the group. Not user's ID. If not successful returns -1.
	 */
	public static function add($groupID, $userID, $role=Role::Member){

        if(!(Role::isValid($role))){
            return -1;
        }

        $mysql = pdodb::getInstance();

        if(Role::isOwner($role)){
            $query = "select OwnerID from fumsocial.Group where GroupID=?";
            $mysql->Prepare($query);
            $res = $mysql->ExecuteStatement(array($groupID));
            if($userID != $res->fetch()["OwnerID"]){
                return -1;
            }
        }

		$query = "insert into fumsocial.Group_member (GroupID, UserID, Role";
        $query .= ", AddedBy";
        $query .= ", JoinedAt";
        $query .= ") values (?, ?, ?, ?";
        $query .= ", ?";
        $query .= ");";

        $valueList = array();
        array_push($valueList, $groupID);
        array_push($valueList, $userID);
        array_push($valueList, $role);
        array_push($valueList, $_SESSION["PersonID"]);

        $joinedAt = date("Y-m-d") ." ". date("H:m:s");
        array_push($valueList, $joinedAt);

        $mysql->Prepare($query);
        $mysql->ExecuteStatement($valueList);
        $lastID = ManageMember::getLastID();
        $mysql->audit("اضافه کردن عضو با کد".$userID . "به گروه با کد".$groupID);
        return $lastID;
	}

    /**
	 * @param groupID Integer ID of the group you want to add member to.
	 * @param memberList List of users' Integer IDs whose going to be added to the group.
	 * @return state If not successful returns -1 else returns 1.
	 */
    public static function addMembers($groupID, $memberList=NULL){
        if($memberList==NULL){
            return -1;
        }
        $mysql = pdodb::getInstance();
        $query = "insert into fumsocial.Group_member (GroupID, UserID, Role";
        $query .= ", JoinedAt)";
        $query .= " values ";

        $valueList = array();

        foreach($memberList as $userID){
            $query .= "(?, ?, ?, ?),";
            array_push($valueList, $groupID);
            array_push($valueList, $userID);
            array_push($valueList, "Member");
            $joinedAt = date("Y-m-d") ." ". date("H:m:s");
            array_push($valueList, $joinedAt);
        }
        $query = substr($query, 0, -1);
        $mysql->Prepare($query);
        $mysql->ExecuteStatement($valueList);
        $mysql->audit("اضافه کردن لیستی از اعضا به گروه با کد".$groupID);

        return 1;

    }


    /**
	 * @param userID Integer ID of the user you are trying to remove.
	 * @param groupID Integer ID of the group you want to remove the user from it.
	 * @return state If not successful returns -1.
	 */
    public static function removeMember($userID, $groupID){

        $mysql = pdodb::getInstance();

        $query = "select OwnerID from fumsocial.Group where GroupID=?";
        $mysql->Prepare($query);
        $res = $mysql->ExecuteStatement(array($groupID));
        if($userID == $res->fetch()["OwnerID"]){
            return -1;
        }

        $removerID = $_SESSION["PersonID"];

        $query = "select Role from fumsocial.Group_member where GroupID=? AND UserID=?";
        $mysql->Prepare($query);
        $query_params = array();
        array_push($query_params, $groupID);
        array_push($query_params, $removerID);
        $res = $mysql->ExecuteStatement($query_params);
        if($rec = $res->fetch()){
            if($rec["Role"]=="Member"){
                return -1;
            }
        }else{
            return -1;
        }

        $query = "delete from fumsocial.Group_member where UserID=? AND GroupID=?";
        $valueList = array();
        array_push($valueList, $userID);
        array_push($valueList, $groupID);
		$mysql->Prepare($query);
		$mysql->ExecuteStatement($valueList);
        $mysql->audit("حذف کردن عضو با کد".$userID . "از گروه با کد".$groupID);
	}

    /**
	 * @param userID Integer ID of the user you are trying to update.
	 * @param groupID Integer ID of the group you want to deploy updates on.
     * @param role Role of the new member. Role has 3 valid values:
     * Owner - is the role of the owner of the group. only one owner is allowed.
     * Admin - is the role of the admins of the group. multiple admins are allowed in a group.
     * Member - defualt role of every member of the group. A group can have multiple members.
	 * @return state If not successful returns -1.
	 */
    public static function updateRole($userID, $groupID, $role){

        $mysql = pdodb::getInstance();        

        $query = "select OwnerID from fumsocial.Group where GroupID=?";
        $mysql->Prepare($query);
        $res = $mysql->ExecuteStatement(array($groupID));
        if($userID == $res->fetch()["OwnerID"]){
            return -1;
        }

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

		$query = "update fumsocial.Group_member set Role=? where UserID=? AND GroupID=?";
        $valueList = array();
        if(Role::isAdmin($role)){
            $adminString = "Admin";
            array_push($valueList, $adminString);
        }elseif(Role::isMember($role)){
            $memberString = "Member";
            array_push($valueList, $memberString);
        }else{
            return -1;
        }
        array_push($valueList, $userID);
        array_push($valueList, $groupID);
        $mysql->Prepare($query);
		$mysql->ExecuteStatement($valueList);
        $mysql->audit("بروزرسانی کردن عضو با کد".$userID . "از گروه با کد".$groupID);
    }

    /**
	 * @param userID Integer ID of the user you are trying to get its role.
	 * @param groupID Integer ID of the group of the user.
     * @return role Role of the member. Role has 3 valid values:
     * Owner - is the role of the owner of the group. only one owner is allowed.
     * Admin - is the role of the admins of the group. multiple admins are allowed in a group.
     * Member - defualt role of every member of the group. A group can have multiple members.
     * if not succesful return -1.
	 */
    public static function getRole($userID, $groupID){
        $mysql = pdodb::getInstance();
        $query = "select Role from fumsocial.Group_member where UserID=? AND GroupID=?";
        $valueList = array();
        array_push($valueList, $userID);
        array_push($valueList, $groupID);
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement($valueList);
        if($rec = $res->fetch()){
            return $rec["Role"];
        }
        return -1;
    }

    /**
	 * @param groupID Integer ID of the group you want to get its admins.
	 * @return state If not successful returns -1.
	 */
    public static function getAdmins($groupID){
        $mysql = pdodb::getInstance();
        $query = "select pfname, plname, Role, MemberID from 
                    sadaf.persons inner join fumsocial.Group_member 
                    on fumsocial.Group_member.UserID=sadaf.persons.PersonID;";
        $mysql->Prepare($query);
        $res = $mysql->ExecuteStatement(array($groupID));

        $ret = array();
        $i=0;
		while($rec=$res->fetch())
		{
			$ret[$i] = new Member();
			$ret[$i]->memberID=$rec["MemberID"];
			$ret[$i]->fname=$rec["fname"];
            $ret[$i]->lname=$rec["lname"];
			$ret[$i]->role=$rec["Role"];
			$i++;
		}
		return $ret;
    }

    /**
	 * @param groupID Integer ID of the group you want to get its members.
	 * @return state If not successful returns -1.
	 */
    public static function getMemberList($groupID){

        $mysql = pdodb::getInstance();
		$ret = array();
		$query = "select pfname, plname, Role, UserID from 
                    sadaf.persons inner join fumsocial.Group_member 
                    on fumsocial.Group_member.UserID=sadaf.persons.PersonID
                    AND fumsocial.Group_member.GroupID=?;";
		$mysql->Prepare($query);
		$res = $mysql->ExecuteStatement(array($groupID));
		$i=0;
		while($rec=$res->fetch())
		{
			$ret[$i] = new Member();
			$ret[$i]->userID=$rec["UserID"];
			$ret[$i]->fname=$rec["pfname"];
            $ret[$i]->lname=$rec["plname"];
			$ret[$i]->role=$rec["Role"];
			$i++;
		}
		return $ret;
    }

}

?>