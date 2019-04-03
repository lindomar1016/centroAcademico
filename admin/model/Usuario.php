<?php


class Usuario{

	private $nome;
	private $email;
	private $senha;


	public function getNome(){
		return $this->nome;
	}
	public function setNome($nome){
		$this->nome = $nome;
	}
	public function getEmail(){
		return $this->email;
	}
	public function setEmail($email){
		$this->email = $email;
	}
	public function getSenha(){
		return $this->senha;
	}
	public function setSenha($senha){
		$this->senha = $senha;
	}

	public function __construct(){


	}
	public function login(){
		if($this->checarLogin()){
			$_SESSION['usuario'] = $this->checarLogin();
			header('Location: ../index.php');
		}else{
			echo 'n exste';
		}
	}

	public function checarLogin(){//Verifica se Existe o login e senha solicitado, retornando os dados do usuario

		try{

			$email = $this->getEmail();
			$senha = $this->getSenha();

			$sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha AND admin = 1";

			$stmt = Conexao::getInstancia()->prepare($sql);

			$stmt->bindValue(":email",$email);
			$stmt->bindValue(":senha",$senha);

			$stmt->execute();

			$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

			return $resultado;

		}catch(Exception $e){
			print "Erro ao acessar Banco de Dados<br>";
			print($e->getMessage());
			return false;
		}

	}

	public function getTodos(){//Retorna todos usuarios que não são administradores
		try{
			$sql = "SELECT * FROM usuarios WHERE admin = 0";

			$stmt = Conexao::getInstancia()->prepare($sql);

			$stmt->execute();

			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $resultado;
		}catch(Exception $e){
			print("Erro ao acessar Banco de Dados<br>");
			print($e->getMessage());
		}
	}

	public function cadastrar(){

		try{

			$nome = $this->getNome();
			$email = $this->getEmail();
			$senha = $this->getSenha();

			$sql = "INSERT INTO usuarios (nome, email, senha) VALUES(:nome,:email,:senha)";

			$stmt = Conexao::getInstancia()->prepare($sql);

			$stmt->bindValue(":nome",$nome);
			$stmt->bindValue(":email",$email);
			$stmt->bindValue(":senha",$senha);

			return $stmt->execute();

		}catch(Exception $e){
			print("Erro ao acessar Banco de Dados<br>");
			print($e->getMessage());
		}
	}

	public function checarEmail(){//Verifica se email ja está cadastrado

		try{

			$email = $this->getEmail();

			$sql 	= "SELECT email FROM usuarios where email = :email";

			$stmt	= Conexao::getInstancia()->prepare($sql);

			$stmt->bindValue(":email",$email);
			$stmt->execute();

			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if(count($resultado) > 0){
				return false;
			}else{
				return true;
			}
		}catch(Exception $e){
			print("Erro ao acessar Banco de Dados<br>");
			print($e->getMessage());
		}
	}
}




