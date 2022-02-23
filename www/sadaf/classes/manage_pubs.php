<?php

class be_publication
{
    public $pubID;
    public $pubName;

    function __construct()
    {
    }
}

class manage_pubs
{
    static function getPersonPubs($PersonID)
    {
        /**
         * اطلاعات انتشارات را از پرتال دریافت میکند (فعلا از جدول publications)
         */
        $pub = array();
        $query = "SELECT id, pub.personID, pub.publication FROM sadaf.publications pub JOIN sadaf.persons per ON pub.personID = per.personID";
        $mysql = pdodb::getInstance();
        $mysql->Prepare ($query);
        $res = $mysql->ExecuteStatement (array ($PersonID) );
        $i = 0;

        while ($rec = $res->fetch()) {
            $pub = new be_publication();
            $pub->pubID = $rec["id"];
            $pub->pubName = $rec["publication"];
            $pubs[$i] = $pub;
            $i++;
        }
        return $pubs;
    }


    static function getList($from, $quantity)
    {
        /**
         * تعداد مشخصی از انتشارات کاربر را دریافت میکند
         */
        if (!is_numeric($from)) $from = 0;
        if (!is_numeric($quantity)) $quantity = 0;

        $skills = array();
        $query = "SELECT * from sadaf.publications";

    }


    static function isDuplicate($personID, $pubID)
    {
        /**
         * چک کردن تکراری بودن انتشار وارد شده
         */
        $mysql = pdodb::getInstance();
        $query = "SELECT * FROM sadaf.persons pers LEFT JOIN sadaf.publications pubs ON (pers.PersonID = pubs.personID) where pubs.personID = ? and pubs.id = ?";
        $mysql->Prepare($query);
        $res = $mysql->ExecuteStatement(array($personID, $pubID));
        if ($rec = $res->fetch()) return true;
        return false;
    }


}

