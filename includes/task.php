<?php

require_once('includes/db.php');

class Task{
	private $dbconn;

	public function __construct($name = NULL, $date = NULL){
		$database = new Db();
		$db = $database->dbConnect();
		$this->dbconn = $db;
	}

	public function getTask($id, $sortby = NULL){
		if( $sortby != NULL ){
			switch ($sortby) {
				case 'abc':
					$query = "SELECT * FROM task WHERE task_owner=:id ORDER BY task_name ASC";
					break;
				case 'dead':
					$query = "SELECT * FROM task WHERE task_owner=:id ORDER BY deadline DESC";
					break;
				case 'status':
					$query = "SELECT * FROM task WHERE task_owner=:id ORDER BY finished DESC";
					break;
				case 'prior':
					$query = "SELECT * FROM task WHERE task_owner=:id ORDER BY prioritet DESC";
					break;
			}
		}
		else {
			$query = "SELECT * FROM task WHERE task_owner=:id ORDER BY deadline DESC";
		}

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

	public function outputTasks($listid, $sortby = NULL) {
		$ispis = '';

		$task = $this->getTask($listid, $sortby);
		foreach ($task as $r) {
			if($r['prioritet'] == '2') {
				$prior = '<i style="color:#E24A37" class="glyphicon glyphicon-menu-up"></i>';
			}
			elseif ($r['prioritet'] == '1') {
				$prior = '<i style="color: #5F5E5D" class="glyphicon glyphicon-minus"></i>';
			}
			else {
				$prior = '<i style="color: green" class="glyphicon glyphicon-menu-down"></i>';
			}

			if($r['finished'] == 1){
				$status = '<i style="color: green" class="glyphicon glyphicon-ok"></i>';
			} else {
				$status = '<i style="color: #E24A37" class="glyphicon glyphicon-minus"></i>';
			}

			$todaydate = new DateTime("now");
			$deadline = new DateTime($r['deadline']);
			$eta = $deadline->diff($todaydate);

			$ispis .= '<div class="single-list" id="single-list-'. $r['id'] .'" data-id="'. $r['id'] . '"><div class="inside-single-task">';
			$ispis .= '<div class="ime-liste">';
			$ispis .= $r['task_name'] . '</div>';
			$ispis .= '<div class="prioritet-task" data-prior="' . $r['prioritet'] . '">' . $prior . '</div>';
			$ispis .= '<div class="datum-list"><i class="glyphicon glyphicon-calendar"></i>&nbsp;' . $r['deadline'] . '</div>';
			$ispis .= '<div class="status-task status-task-' . $r['id'] . '">' . $status . '</div>';
			$ispis .= '<div class="eta-task"><i class="glyphicon glyphicon-time"></i>&nbsp;' . $eta->format("%R%a") . '</div>';

			$ispis .= '<div class="edit-task"><a class="edit-button" data-id="' . $r['id'] . '" href="#"><i title="edit" class="glyphicon glyphicon-edit"></i></a></div>';
			$ispis .= '<div class="check-task"><a class="check-button" data-status="' . $r['finished'] . '" data-id="' . $r['id'] . '" href="#"><i title="završeno" class="glyphicon glyphicon-check"></i></a></div>';
			$ispis .='<div class="delete-list"><a class="delete-button" data-id="' . $r['id'] . '" href="#"><i title="izbriši task" class="glyphicon glyphicon-remove"></i></a></div>';
			$ispis .= '</div></div>';
		}

		return $ispis;
	}

	public function checkTask($id){
		$checkstat = "SELECT finished FROM task WHERE id=:taskid";
		$checkstat = $this->dbconn->prepare($checkstat);
		$checkstat->execute(array(":taskid"=>$id));
		$currstat = $checkstat->fetch(PDO::FETCH_ASSOC);

		if ($currstat['finished'] == 0){
			$query = "UPDATE task
						SET finished='1'
						WHERE id=:taskid";
		}
		else {
			$query = "UPDATE task
						SET finished='0'
						WHERE id=:taskid";
		}
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":taskid"=>$id));
	}

	public function deleteTask($id){
		$query = "DELETE FROM task
					WHERE id=:taskid";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":taskid"=>$id));
	}

	public function addTask($taskname, $prioritet, $deadline, $listid) {
		$date = date("Y-m-d H:i", strtotime($deadline));
		$datum = substr($date, 0, -4);
		$query = "INSERT INTO task (task_name, prioritet, deadline, task_owner, finished)
					VALUES ('$taskname', $prioritet, :ovidatum, $listid, '0')";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":ovidatum"=>$datum));
	}

	public function editTask($taskname, $prioritet, $deadline, $listid, $taskid){
		$date = date("Y-m-d H:i", strtotime($deadline));
		$datum = substr($date, 0, -4);	
		$query = "UPDATE task
					SET task_name=:name, prioritet=:prior, deadline=:dead
					WHERE id='$taskid'";
		$query = $this->dbconn->prepare($query);
		$query->execute(array(":name"=>$taskname, ":prior"=>$prioritet, ":dead"=>$deadline));
	}
}