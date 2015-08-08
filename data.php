<?php

include_once("DB.class.php");

set_time_limit(0);

$db = new DB;

date_default_timezone_set('America/Araguaina');

switch($_POST["act"]){
	case "postar":
	  
	  $nome = $_POST["nome"];
	  $texto = $_POST["texto"];
	  
	  $time = time();
	  $data = date("h:i:s");
	  
	  if(empty($nome)){
		  echo json_encode(array("msg" => "1"));
	  }else if(empty($texto)){
		  echo json_encode(array("msg" => "2"));
	  }else{
		  
		  $insert = $db->con()->prepare("INSERT INTO post (nome, texto, data, timestamp) VALUES (?, ?, ?, ?)");
		  $insert->execute(array($nome, $texto, $data, $time));
		  
		  if($insert = 1){
			  echo json_encode(array("res" => "Publicado com sucesso!", "res_msg" => "1"));
			 
		  }else{
			  echo json_encode(array("res" => "Ocorreu um erro ao publicar, tente novamente mais tarde!", "res_msg" => "2"));
		  }
		  
	  }
	  
	break;
	
	case "LastPost":
	   $sql = $db->con()->prepare("SELECT nome, texto, data FROM post ORDER BY id DESC LIMIT 1");
	   $sql->execute();
	   
	   $ftc = $sql->fetchObject();
	   
	   if($sql){
		   $json = json_encode(array("code" => "1","htmln" =>$ftc->nome, "htmlt" => $ftc->texto, "htmld" => $ftc->data));
		   echo $json;
	   }
   break;
   
   case "allPosts":
	   $stmt = $db->con()->prepare("SELECT nome, texto, data FROM post ORDER BY id DESC");
	   $stmt->execute();
	   
	   if($stmt){
		 while($ftc = $stmt->fetchObject()){
		   
		   $html = '';
		   
			$html .= '<div class="blk-pst">';
			  $html .= '<div class="ip-info">';
				 $html .= '<img src="lib/img/default.png" />';
				 $html .= '<div class="nm">'.$ftc->nome.'</div>';
				 $html .= '<i class="clear"></i>';
				 $html .= '<br />';
				 $html .= '<div class="hour">'.$ftc->data.'</div>';
			  $html .= '</div>';
			  $html .= '<div class="clear"></div>';
			  $html .= '<div class="txt">';
				$html .= $ftc->texto;
			  $html .= '</div>';
			$html .= '</div>';	
			
			echo $html;
		 }
	   }
   break;
   
   case "realtime":
     $lastTime = isset($_POST['timestamp']) ? (int)$_POST['timestamp'] : time();
	 
	 while(true){
		 
		 $sql = $db->con()->prepare("SELECT nome, texto, data FROM post WHERE timestamp >= ?");
		 $sql->execute(array($lastTime));
		 
		 $row = $sql->fetchAll(PDO::FETCH_ASSOC);
		 
		 $stmt = $db->con()->prepare("SELECT nome, texto, data FROM post ORDER BY id DESC");
		 $stmt->execute();
		 
		 if(count($row) > 0){
			 
			while($ftc = $stmt->fetchObject()){
				$html = '';
		   
				$html .= '<div class="blk-pst">';
				  $html .= '<div class="ip-info">';
					 $html .= '<img src="lib/img/default.png" />';
					 $html .= '<div class="nm">'.$ftc->nome.'</div>';
					 $html .= '<i class="clear"></i>';
					 $html .= '<br />';
					 $html .= '<div class="hour">'.$ftc->data.'</div>';
				  $html .= '</div>';
				  $html .= '<div class="clear"></div>';
				  $html .= '<div class="txt">';
					$html .= $ftc->texto;
				  $html .= '</div>';
				$html .= '</div>';	
				
				echo $html;
			}
			
			 break;
		 }else{
			 echo "";
			 sleep(5);
			 continue;
		 }
		
	 }
	 
   break;
}