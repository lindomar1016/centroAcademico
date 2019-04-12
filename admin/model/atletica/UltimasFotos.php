<?php

class UltimasFotos{

	private $titulo;
	private $descricao;
	private $foto;
	private $data_evento;

	public function getTitulo()
	{
		return $this->titulo;
	}

	public function setTitulo($titulo)
	{
		$this->titulo = $titulo;
	}

	public function getData_evento()
	{
		return $this->data_evento;
	}

	public function setData_evento($data_evento)
	{
		$this->data_evento = $data_evento;
	}

	public function getDescricao()
	{
		return $this->descricao;
	}
	
	public function setDescricao($descricao)
	{
		$this->descricao = $descricao;
	}


	public function getFoto()
	{
		return $this->foto;
	}
	
	public function setFoto($foto)
	{
		$this->foto = $foto;
	}

	public function cadastrar(){

		$sql = "INSERT INTO fotos_atletica (titulo,descricao,foto,data_evento) VALUES(
		:titulo,:descricao,:foto,:data_evento)";

		$stmt = Conexao::getInstancia()->prepare($sql);

		$foto = $this->salvarImagem($this->getFoto());

		
		if($foto === true){
			$stmt->bindValue(":titulo",$this->getTitulo());
			$stmt->bindValue(":descricao",$this->getDescricao());
			$stmt->bindValue(":data_evento",$this->getData_evento());
			$stmt->bindValue(":foto",$this->getFoto());

			return $stmt->execute();	
		}else{
			var_dump($foto);
			return $foto;
		}
	}

	public function editar($id){

		if($this->getFoto()){//Verifica se o usuário enviou alguma foto e salva a imagem
			$fotoAntiga = $this->buscarPorID($id);
			if($this->salvarImagem($this->getFoto())){
				unlink('../../dist/img/atletica/ultimas_fotos/' .$fotoAntiga['foto']);
			}
			$foto = $this->getFoto();
			$sql = "UPDATE fotos_atletica SET titulo = :titulo,descricao = :descricao,data_evento = :data_evento, foto = :foto WHERE id = :id";
			$stmt = Conexao::getInstancia()->prepare($sql);
			$stmt->bindValue(":foto",$foto);
		}else{
			$sql = "UPDATE fotos_atletica SET titulo = :titulo,descricao = :descricao,data_evento = :data_evento WHERE id = :id";
			$stmt = Conexao::getInstancia()->prepare($sql);
		}

		$stmt->bindValue(":titulo",$this->getTitulo());
		$stmt->bindValue(":descricao",$this->getDescricao());
		$stmt->bindValue(":data_evento",$this->getData_evento());
		$stmt->bindValue(":id",$id);

		return $stmt->execute();	
	}

	public function salvarImagem($imagem){

		// verifica se foi enviado um arquivo
		if ( isset( $imagem[ 'name' ] ) && $imagem[ 'error' ] == 0 ) {

			$arquivo_tmp = $imagem[ 'tmp_name' ];
			$nome = $imagem[ 'name' ];

		    // Pega a extensão
			$extensao = pathinfo ( $nome, PATHINFO_EXTENSION );

		    // Converte a extensão para minúsculo
			$extensao = strtolower ( $extensao );

		    // Somente imagens, .jpg;.jpeg;.gif;.png
		    // Aqui eu enfileiro as extensões permitidas e separo por ';'
		    // Isso serve apenas para eu poder pesquisar dentro desta String
			if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) && $imagem['size'] <= 5000000) {
		        // Cria um nome único para esta imagem
		        // Evita que duplique as imagens no servidor.
		        // Evita nomes com acentos, espaços e caracteres não alfanuméricos
				$novoNome = uniqid ( time () ) . '.' . $extensao;

		        // Concatena a pasta com o nome
				$destino = '../../dist/img/atletica/ultimas_fotos/' . $novoNome;

		        // tenta mover o arquivo para o destino
				if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {
					echo 'Arquivo salvo com sucesso em : <strong>' . $destino . '</strong><br />';
					echo ' < img src = "' . $destino . '" />';
					$this->setFoto($novoNome);
					return true;
				}
				else
					return 2;
				//Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.
			}
			else
				return 3;
			//Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png", com tamanho maximo de 5Mb
		}
		else
			return 4;
		//'Você não enviou nenhum arquivo!'
	}

	// public function buscarProduto($termo){

	// 	try{

	// 		$termo = "%".$termo."%";
	// 		$sql = "SELECT * FROM produtos where nome LIKE :termo ORDER BY nome ";

	// 		$stmt = Conexao::getInstancia()->prepare($sql);

	// 		$stmt->bindValue(":termo",$termo);

	// 		$stmt->execute();

	// 		$consulta = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// 		return $consulta;

	// 	}catch(Exception $e){
	// 		print("Erro ao acessar Banco de Dados<br>");
	// 		print($e->getMessage());
	// 	}
	// }

	public function buscarTodas($opcoes = 'DESC LIMIT 6'){

		try{

			$sql = "SELECT * FROM fotos_atletica ORDER BY dt_cadastro $opcoes";

			$stmt = Conexao::getInstancia()->prepare($sql);

			$stmt->execute();

			$consulta = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $consulta;

		}catch(Exception $e){
			print("Erro ao acessar Banco de Dados<br>");
			print($e->getMessage());
		}
	}

	public function buscarPorID($id){
		try{

			$sql = "SELECT * FROM fotos_atletica where id = :id";

			$stmt = Conexao::getInstancia()->prepare($sql);

			$stmt->bindValue(":id",$id);

			$stmt->execute();

			$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

			return $consulta;

		}catch(Exception $e){
			print("Erro ao acessar Banco de Dados<br>");
			print($e->getMessage());
		}
	}
	public function deletar($id){

		try{

			$sql = "DELETE FROM produtos WHERE id = :id";

			$stmt = Conexao::getInstancia()->prepare($sql);

			$stmt->bindValue(':id',$id);

			return $stmt->execute();

		}catch(Exception $e){
			print("Erro ao acessar Banco de Dados<br>");
			print($e->getMessage());
		}
	}
}