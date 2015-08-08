# feed

Neste projeto temos um mini feed, criado na linguagem PHP com Javascript. 
Nele temos um arquivo .html que irá receber os callback.

Como vocês sabem, o arquivo DB.class.php é o responsavel pela conexão com o servidor.
Já o nosso arquivo data.php é o responsavel pelo processo cliente -> servidor;

Por último temos um arquivo functions.js ele é o responsavel pela manipulação DOM e por as requisições ao arquivo data.php.

no nosso arquivo functions.js temos:


$('body').on("click", ".pst", function(e){
	$value = $('.postVal').val();
	$stt    = $('.stt').html();
	
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

Inicialmente criamos duas variaveis, uma para recuperar o valor do campo de texto ($value) e outra pra recuperar o nome que o usuário definiu ($stt).
Depois da verificação básica (ver se o nome foi definido, ver se o campo de texto estar vazio...) eles faz uma requisição para o nosso
arquivo data.php, essa requisição faz uma postagem e ao receber o valor "1" de "sucesso" ele chama a função getLastPost(); que 
retorna a última postagem inserida no BD (banco de dados), claramente sendo a última, no caso é a que você acabou de inserir.

Ainda nesse arquivo, temos mais 2 funções, uma que retorna todas as postagens ( getAllPosts() ) e a outra é o nosso LP (longpolling) (getContentPost()  )
para trabalharmos com um feed em realtime (tempo real).

# O Long polling

Nosso long polling não é tão avançado, mas também não é tão simples, aliás é simples, mas bem funcional.
Como vocês devem saber, o LP mantém uma conexão aberta com o servidor, esperando uma nova resposta.
Para otimizar eu apenas verifiquei se tem alguma resposta, caso tenha, ele retorna o resultado pro cliente, caso não aja resposta
ele fecha a conexão, e depois de certo tempo abre outra conexão.

Poderiamos ter melhorado, caso você queira melhorar o Long Polling, eu aconselho você ver essa publicação no imasters:
http://imasters.com.br/linguagens/php/dicas-para-melhorar-o-long-polling-com-php/

crédito da publicação no site: Andrey Knupp Vital

# Próximas atualizações

Nas próximas atualizações do feed quero implementar o feed com o sitema de compartilhar, curtir, comentar e talvez colocaremos 
um de login e cadastro só pra ficar algo bem bonito e completo :D

Até a próxima...
