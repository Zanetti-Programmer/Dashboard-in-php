<?php
include 'index.php'; // Inclui o arquivo 'index.php'

try {
    $pdo = new PDO("firebird:dbname=172.16.2.2:dbintegra", "SYSDBA", "jabuti");


    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se a requisição é do tipo POST
        $dt_ini = $_POST['dt_ini']; // Obtém o valor do campo 'dt_ini' do formulário
        $dt_fim = $_POST['dt_fim']; // Obtém o valor do campo 'dt_fim' do formulário
        $cd_req = $_POST['req'];    // Obtém o valor do campo 'req' do formulário
    }
    

    // // Defina os valores dos parâmetros
    // $dt_ini = '2023-07-01'; // Substitua pelo valor desejado
    // $dt_fim = '2023-08-03'; // Substitua pelo valor desejado
    // $cd_req= '0'; // Substitua pelo valor desejado ( usei para fazer teste com parametros ja defenido)

    function utf8_encode_recursive(array $array)
{
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result[$key] = utf8_encode_recursive($value);
        } else {
            $result[$key] = utf8_encode($value);
        }
    }
    return $result;
}

    // Prepare a consulta SQL com os parâmetros
    $sql = "SELECT DISTINCT curso.CD_CSO ,curso.NM_CSO , a.PER_GDE ,a.NM_ALU ,s.CD_SET , s.NM_SET , /*tinha dois select*/
    s2.CD_SET AS cd_setor_pai, s2.NM_SET AS setor_pai, tp.CD_REQ ,  tp.DESCR_REQUERIMENTO , p.NUM_PROT , 
    p.DT_ABERT , p.HR_ABERT ,p.SITUACAO ,p.DATA_ENTREGA ,p.HORA_ENTREGA,p.CD_USU,o.NM_USU  
    FROM PROTOCOLO p
    JOIN ALUNO a ON a.CD_ALU =p.CD_ALU 
    JOIN curso ON curso.CD_CSO =a.CD_CSO
    JOIN TIPO_PROTOCOLO tp ON tp.CD_REQ = p.CD_REQ 
    JOIN SETOR s ON s.CD_SET =p.CD_SET 
    LEFT JOIN OPERADOR o ON o.CD_USU =p.CD_USU 
    LEFT JOIN SETOR s2 ON s2.CD_SET =s.SETOR_PAI---
    WHERE p.DT_ABERT BETWEEN :dt_ini AND :dt_fim
    AND p.cd_req =:req
    ORDER BY p.DT_ABERT, p.HR_ABERT";

    $stmt = $pdo->prepare($sql);

    // Substitua os parâmetros na consulta preparada
    $stmt->bindParam(':dt_ini', $dt_ini);
    $stmt->bindParam(':dt_fim', $dt_fim);
    $stmt->bindParam(':req', $cd_req);

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numLinhas = $stmt->rowCount();
    $results = utf8_encode_recursive($results);

    

    $NM_USU = array();
    $CD_REQ = array();
    $DESCR_REQUERIMENTO = array();
    $NM_CSO = array();

    foreach ($results as $dados) {
        $NM_USU[] = $dados['NM_USU'];
        $CD_REQ[] = $dados['CD_REQ'];
        $DESCR_REQUERIMENTO[]=$dados['DESCR_REQUERIMENTO'];
        $NM_CSO[]=$dados['NM_CSO'];
        // echo $dados['NM_CSO'];
    }

    $NOMECURSO = array_count_values($NM_CSO);

foreach($NOMECURSO as $nmCurso => $quantidadecurso) {
    // echo ("o nome do curso $nmCurso quantidade pro curso:$quantidadecurso<br> ");
}


} catch (PDOException $e) {
    echo "Erro ao executar a consulta: " . $e->getMessage();
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
<input class="caixa-texto" type="text" disabled value="<?php echo "Requerimento Por Curso"?>">
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
      labels:<?php echo json_encode(array_keys($NOMECURSO)) ?>,
      datasets: [{
        label:<?php echo json_encode($dados['DESCR_REQUERIMENTO']) ?>,
        data: <?php echo json_encode(array_values($NOMECURSO)) ?>,
        borderWidth: 1
      }]
    },
    options: {
      indexAxis: 'y',
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

