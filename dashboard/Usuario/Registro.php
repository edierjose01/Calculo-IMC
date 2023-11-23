<?php 
    require_once('Core/autoload.php');
    class Registro extends Conexion
    {
        private $peso;
        private $altura;
        private $imc;
        private $estado;
		

        function __construct()
        {
            parent::__construct();
        }
 
		public function insertarDatos(float $peso, float $altura, float $imc, string $estado, int $paciente_id, string $nombre_paciente)
		{
			$this->peso = $peso;
			$this->altura = $altura;
			$this->imc = $imc;
			$this->estado = $estado;
			$this->id = $paciente_id;
			$this->nombre = $nombre_paciente;
		
			$query = "INSERT INTO calculo_imc (peso_usuario, altura_usuario, imc_usuario, estado_usuario, paciente_id, nombre_paciente) VALUES (?, ?, ?, ?, ?, ?)";
			$prepare = $this->conex->prepare($query);
			$arrData = [$this->peso, $this->altura, $this->imc, $this->estado, $this->id, $this->nombre];
			$result = $prepare->execute($arrData);
			$idResult = $this->conex->lastInsertId();
		
			return $idResult;
		}
		

		public function obtenerRegistros()
		{
			$query = "SELECT p.id AS paciente_id, p.nombre AS nombre_paciente, c.peso_usuario, c.altura_usuario, c.imc_usuario, c.estado_usuario 
			FROM calculo_imc c
			INNER JOIN paciente p ON c.paciente_id = p.id
			ORDER BY c.imc_usuario ASC";
  
			
			$result = $this->conex->query($query);
			$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		
			if (count($rows) > 0) {
				return $rows;
			} else {
				return "No hay pacientes registrados";
			}
		}

		public function obtenerPacientes()
		{
			$query = "SELECT * FROM db_imc.paciente";
			$result = $this->conex->query($query);
			$rows = $result->fetchAll(PDO::FETCH_ASSOC);
	
			return $rows;
		}
		
    }
?>

