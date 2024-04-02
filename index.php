<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gráfico de Requerimentos</title>
  <style>
    .container {
      background-color: #d9d9d9 ;
    }
  </style>
</head>
<body>



<div class="div1">


<fieldset>

<div class="col-4">
  <form method="post" id="meuFormulario">
    <!-- Abertura do formulário com método POST e ID "meuFormulario" -->
    Data Inicial: <input type="date" id="dt_ini" name="dt_ini" class='caixa-texto' min="" max="" required>
    
  </div>

  <div class="col-4">
    Data Final: <input type="date" name="dt_fim" class='caixa-texto' value="<?php echo date("Y-m-d"); ?>" required>
    </div>

<div class="col-4">
Requerimento: <select name="req" class='caixa-texto meuSelect' id="meuSelect" required>
  <!-- quando usar id em java é # e class é so o "." -->
<option>Selecione uma opção</option>
<option value="0">Requerimento de Matricula (Contrato)</option>
<option value="1">Requerimento Confirmacao de Matricula</option>
<option value="2">Comparecimento</option>
<option value="3">Realizações de exames</option>
<option value="4">Atestado de Matricula</option>
<option value="5">Conclusão de Curso</option>
<option value="6">Colação de Grau</option>
<option value="7">Notas</option>
<option value="8">Taxas Pagas</option>
<option value="9">Curriculo</option>
<option value="10">Estágio Supervisionado</option>
<option value="11">Atestado de Vaga</option>
<option value="12">Atestado de Curso</option>
<option value="13">Dispensa de Disciplina</option>
<option value="14">Inclusão de Disciplina</option>
<option value="15">Trancamento de Matricula</option>
<option value="16">Guia de Transferencia</option>
<option value="17">Pedido Dependencia</option>
<option value="18">Pedido Adaptacao</option>
<option value="19">Programas de Curso</option>
<option value="20">Historico Escolar</option>
<option value="21">Expedicao Diploma-Simples</option>
<option value="22">Expedicao Diploma-Pele</option>
<option value="23">Segunda via da Carteirinha</option>
<option value="24">Prova Substitutiva</option>
<option value="25">Requerimento Adaptação/Dependência</option>
<option value="26">Cancelamento do Curso</option>
<option value="27">Licenca Maternidade-90 D.</option>
<option value="28">Outro</option>
<option value="29">Pedido de Troca de Curso</option>
<option value="30">Atendimento</option>
</select>
</div>
<div class="col-4">
<input type="checkbox" name="redirecionar" value="1"> Requerimento Por atendente
<br>
<input type="checkbox" name="redireciona" value="2">Requerimento Por curso<br>

    <button class="glow-on-hover" type="submit" style="color:black">Gerar Gráfico</button>
    </div>
  </form>

  </fieldset>
  </div>

  <script>
  // Adiciona um ouvinte de evento para o envio do formulário
  document.querySelector('#meuFormulario').addEventListener('submit', function(event) {
    // Obtém a referência à caixa de seleção "Requerimento Por atendente"
    const checkboxRedirecionar = document.querySelector('input[name="redirecionar"]');
    const checkboxRedireciona = document.querySelector('input[name="redireciona"');
    
    // Verifica se a caixa de seleção está marcada
    if (checkboxRedirecionar.checked) {
      // Define a ação do formulário para "graf2.php" se a caixa estiver marcada
      this.action = "graf2.php";
    } else if(checkboxRedireciona.checked){
      // Define a ação do formulário para "graf.php" se a caixa não estiver marcada
      this.action = "graf3.php";
    }else{
        this.action = "graf.php";
    }
  });
</script>
  
<script>
  // Função para validar o formulário
  function validarFormulario(event) {
    // Obtém o valor do elemento de seleção (dropdown) com o nome "req"
    const requerimentoSelecionado = document.querySelector('select[name="req"]').value;

    // Verifica se o valor selecionado é igual à opção padrão "Selecione uma opção"
    if (requerimentoSelecionado === "Selecione uma opção") {
      // Se for igual, exibe um alerta ao usuário
      alert("Por favor, selecione um requerimento antes de continuar.");

      // Impede o envio do formulário
      event.preventDefault();

      // Retorna para interromper a execução adicional
      return;
    }
  }

  // Adiciona um ouvinte de evento para o envio do formulário
  document.querySelector('form').addEventListener('submit', validarFormulario);
</script>


<script>
  // Seleciona todas as opções dentro do elemento com o ID "meuSelect"
  const options = document.querySelectorAll('.meuSelect option');
  
  // Para cada opção, adiciona a classe CSS 'form-select'
  options.forEach(option => option.classList.add('form-select'));
</script>

