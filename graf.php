<?php
include 'index.php'; // Inclui o arquivo 'index.php'

// Conexão com o banco de dados
$pdo = new PDO("firebird:dbname=172.16.2.2:dbintegra", "SYSDBA", "jabuti");

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se a requisição é do tipo POST
  $dt_ini = $_POST['dt_ini']; // Obtém o valor do campo 'dt_ini' do formulário
  $dt_fim = $_POST['dt_fim']; // Obtém o valor do campo 'dt_fim' do formulário
  $cd_req = $_POST['req'];    // Obtém o valor do campo 'req' do formulário
}



// Monta a consulta SQL para buscar os dados do banco
$sql = "SELECT DISTINCT curso.CD_CSO, curso.NM_CSO, a.PER_GDE, a.NM_ALU, s.CD_SET, s.NM_SET,
        s2.CD_SET AS cd_setor_pai, s2.NM_SET AS setor_pai, tp.CD_REQ, tp.DESCR_REQUERIMENTO, p.NUM_PROT,
        p.DT_ABERT, p.HR_ABERT, p.SITUACAO, p.DATA_ENTREGA, p.HORA_ENTREGA, p.CD_USU, o.NM_USU
        FROM PROTOCOLO p
        JOIN ALUNO a ON a.CD_ALU = p.CD_ALU
        JOIN curso ON curso.CD_CSO = a.CD_CSO
        JOIN TIPO_PROTOCOLO tp ON tp.CD_REQ = p.CD_REQ
        JOIN SETOR s ON s.CD_SET = p.CD_SET
        LEFT JOIN OPERADOR o ON o.CD_USU = p.CD_USU
        LEFT JOIN SETOR s2 ON s2.CD_SET = s.SETOR_PAI
        WHERE p.DT_ABERT BETWEEN :dt_ini AND :dt_fim
        AND p.cd_req = :req
        ORDER BY p.DT_ABERT, p.HR_ABERT"; // A consulta SQL foi truncada para simplificação

$stmt = $pdo->prepare($sql); // Prepara a consulta SQL para execução
$stmt->bindParam(':dt_ini', $dt_ini); // Associa o parâmetro :dt_ini ao valor da variável $dt_ini
$stmt->bindParam(':dt_fim', $dt_fim); // Associa o parâmetro :dt_fim ao valor da variável $dt_fim
$stmt->bindParam(':req', $cd_req);     // Associa o parâmetro :req ao valor da variável $cd_req
$stmt->execute(); // Executa a consulta SQL
$results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém os resultados da consulta em forma de array associativo
$numLinhas = $stmt->rowCount(); // Obtém o número de linhas retornadas pela consulta

// Inicializa arrays vazios para armazenar dados específicos dos resultados
$NM_USU = array();
$CD_REQ = array();
$DESCR_REQUERIMENTO = array();
$dt_abert = array();


// Preenche os arrays com valores específicos dos resultados
foreach ($results as $dados) {
  $NM_USU[] = $dados['NM_USU'];
  $CD_REQ[] = $dados['CD_REQ'];
  $DESCR_REQUERIMENTO[] = $dados['DESCR_REQUERIMENTO'];
  $dt_abert[] = $dados['DT_ABERT'];
}

$NOMEDATA = array_count_values($dt_abert);

foreach ($NOMEDATA as $data => $quantData) {
}


?>
<div class="1">
  <div class="col-4">
    <label>Data Inicial Solicitada:</label>
    <input class='caixa-texto' type="text" name="dt_ini" disabled value="<?php echo isset($_POST['dt_ini']) ? $_POST['dt_ini'] : ''; ?>"><br>
  </div>
  <div class="col-4">
    <label>Data Final Solicitada:</label>
    <input class="caixa-texto" type="text" name="dt_fim" disabled value="<?php echo isset($_POST['dt_fim']) ? $_POST['dt_fim'] : ''; ?>"><br>
  </div>
</div>
<div class="col-4">
  <input class="caixa-texto" type="text" disabled value="<?php echo "Total Requerimento"?>">
</div>

<div class="container">
  <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode(array_keys($NOMEDATA)) ?>,
      datasets: [{
        label: <?php echo json_encode($dados['DESCR_REQUERIMENTO']) ?>,
        data: <?php echo json_encode(array_values($NOMEDATA)) ?>,
        borderWidth: 1,
        backgroundColor:['rgb(255, 165, 0)']
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<div class="wins">
  <a href="index.php">
    <input type="submit" value="Sair" class="Sair">
  </a>
</div>