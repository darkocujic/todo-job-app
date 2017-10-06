<?php

require_once('includes/db.php');

class TodoList {
	private $dbconn;

	public function __construct($name = NULL, $date = NULL){
		$database = new Db();
		$db = $database->dbConnect();
		$this->dbconn = $db;
	}

	public function printAll($sortby = NULL){
		$result = array();
		$userid = $_SESSION['user_session'];


		if($sortby != NULL){
			if($sortby == "date"){
				$query = "SELECT *
					FROM todo
					WHERE list_owner=:userid
					ORDER BY create_date DESC";
			}
			else if ($sortby == "abc"){
				$query = "SELECT *
					FROM todo
					WHERE list_owner=:userid
					ORDER BY list_name ASC";
			}
		}
		else {
			$query = "SELECT *
						FROM todo
						WHERE list_owner=:userid
						ORDER BY create_date DESC";
		}

		$query = $this->dbconn->prepare($query);
		$query->execute(array(":userid"=>$userid));


		if ($task = $query){
			if ($task->rowCount() ){
				while ($row = $task->fetch(PDO::FETCH_ASSOC)){
					$result[] = $row;
				}
			}
		}
		return $result;
	}

	public function getSingleList($id){
		$query = "SELECT *
					FROM todo
					WHERE id=:listid";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":listid"=>$id));

		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	public function outputList($sortby = NULL){
		$ispis = '';
		$task = $this->printAll($sortby);
		foreach ($task as $r){
			$tasknr = count($this->getTask($r['id']));
			$unfinished = count($this->getUnfinishedTask($r['id']));
			$ispis .= '<div class="single-list"><div>';
			$ispis .= '<a href="single.php?id=' . $r['id'] . '">';
			$ispis .= '<div class="ime-liste">';
			$ispis .= $r['list_name'] . '</div>';
			$ispis .= '<div class="datum-list"><i class="glyphicon glyphicon-calendar"></i>&nbsp;' . $r['create_date'];
			$ispis .= '</div>';
			$ispis .= '<div class="broj-taskova"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $tasknr;
			$ispis .= '</div>';
			$ispis .= '<div class="broj-nedovrsenih"><i class="glyphicon glyphicon-remove-sign"></i>&nbsp;' . $unfinished;
			$ispis .= '</div></a>';
			$ispis .='<div class="delete-list"><a class="delete-button" data-id="' . $r['id'] . '" href="#"><i class="glyphicon glyphicon-remove"></i></a></div>';
			$ispis .= '</div></div>';
		}
		return $ispis;
	}

	public function getTask($id){
		$query = "SELECT * FROM task WHERE task_owner=:id";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":id"=>$id));
		$result = array();
		if ($task = $query){
			if ($task->rowCount() ){
				while ($row = $task->fetch(PDO::FETCH_ASSOC)){
					$result[] = $row;
				}
			}
		}
		return $result;
	}

	public function getUnfinishedTask($id){
		$query = "SELECT * FROM task WHERE task_owner=:id AND finished=0";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":id"=>$id));
		$result = array();
		if ($task = $query){
			if ($task->rowCount() ){
				while ($row = $task->fetch(PDO::FETCH_ASSOC)){
					$result[] = $row;
				}
			}
		}
		return $result;
	}

	public function deleteList($listid){
		$query = "DELETE FROM todo WHERE id=:listid";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":listid"=>$listid));

		$querytwo = "DELETE FROM task WHERE task_owner=:listid";
		$querytwo = $this->dbconn->prepare($querytwo);
		$querytwo->execute(array(":listid"=>$listid));
	}

	public function addNewList($listname, $userid){
		$query = "INSERT INTO todo(list_name, list_owner, create_date)
					VALUES (:listname, :userid, NOW())";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":listname"=>$listname, ":userid"=>$userid));

		$rowid = $this->dbconn->lastInsertId();

		return $rowid;
	}

	
}