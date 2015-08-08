$(document).ready(function(){
	
	/*postagem*/
	
	$('body').on("click", ".pst", function(e){
		$value = $('.postVal').val();
		$stt    = $('.stt').html();
		
		//alert($value);
		
		if($value == ""){
			$('.postVal').slideUp("medium").delay(100).slideDown("fast").attr("placeholder", "Escreva uma mensagem...");
		}else if($stt == ""){
			alert("Clique em usar como... para definir um nome!");
		}else{
			$.ajax({
				url: "data.php",
				type: "POST",
				data: {nome:$stt, texto: $value, act:"postar"},
				dataType: "json",
				
				success: function(d){
					if(d.num == "1"){
						alert("Clique em usar como... para definir um nome!");
					}else if(d.num == "2"){
						alert("Escreva uma mensagem...");
					}else{
						if(d.res_msg == "1"){
							
							$('.postVal').val("");
							
							getLastPost();
							
						}else if(d.res_msg == "2"){
							alert(d.res);
						}
					}
					
				}
			});
		}
		
	});
	
	$('body').on("click", ".pst-cng", function(){
		$promptName = prompt("Qual o seu nome ?");
		
		if(!$promptName){
			alert("Por favor, insira um nome válido!");
		}else{
			
			if($promptName.length < 6 || $promptName >= 31){
				alert("Seu nome deve conter no mínimo 6 caracteres e no máximo 30 caracteres!");
			}else{
				$('.stt').html($promptName);
			}
		}
		
	});
	
	//Mostrar todos os posts
	getAllPosts();
	
	//realtime Posts
	getContentPost();
	
});

// JAVASCRIPT CODE //

	
function getLastPost(){
	$.ajax({
		url: "data.php",
		type: "POST",
		data: {act: "LastPost"},
		dataType: "json",
		
		success: function(s){
			if(s.code == "1"){
				var $html = '';
				
				$html += '<div class="blk-pst">';
				  $html += '<div class="ip-info">';
					 $html += '<img src="lib/img/default.png" />';
					 $html += '<div class="nm">'+s.htmln+'</div>';
					 $html += '<i class="clear"></i>';
					 $html += '<br />';
					 $html += '<div class="hour">'+s.htmld+'</div>';
				  $html += '</div>';
				  $html += '<div class="clear"></div>';
				  $html += '<div class="txt">';
					$html += s.htmlt;
				  $html += '</div>';
				$html += '</div>';	
				
				$("#box-pst").prepend($html);
			}
		}
	});
}

function getAllPosts(){
		$.ajax({
			url: "data.php",
			type: "POST",
			data: {act: "allPosts"},
			
			success: function(s){
				$("#box-pst").prepend(s);
			}
		});
}

function getContentPost(timestamp){
	
	$.ajax({
		url: "data.php",
		type: "POST",
		data: {act: 'realtime', timestamp: timestamp},
		
		success: function(ret){
			if(ret == ""){
				//window.setTimeout("getContentPost", 20000);
			}else{
				$("#box-pst").html(ret);
				getContentPost();
			}	
		}
	});
	
	
	
}