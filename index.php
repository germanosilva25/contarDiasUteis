<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contagem de dias uteis</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>Contar dias uteis</h2>
  <form action="#" method="post">
    <div class="mb-3 mt-3">
      <label for="data">Data início</label>
      <input type="date" class="form-control" id="data" placeholder="Enter email" name="data">
    </div>
    
   
    <button type="submit" class="btn btn-primary">Enviar</button>
  </form>
</div>

</body>
</html>

<?php

if(!$_POST)
    return;
$array = explode("/", $_POST['data']);
$dataIncicio = $_POST['data'];
//echo "data post: ",$dataIncicio;
if($_POST){
    $mes = intval(date('m', strtotime($_POST['data'])));
    
    $contaFeriados = new utilDates();

    echo "O mês de: " .$contaFeriados::mesesReferencia()[$mes-1]. " tem " .$contaFeriados::getDiasUteisMes($_POST['data'], $dataFim = ''). " dias uteis, a partir da data informada!";
}
class utilDates
{
    
    private static $qnt = 1;
    public static $feriados = [
        '01-01', //confraternizacao mundial
        '01-05', //dia do trabalhador
        '21-04', //Tiradentes
        '23-04', //S?o Jorge
        '24-06', //S?o Joao
        '07-09', //Independencia
        '12-10', //Aparecida
        '02-11', //Finados
        '15-11', //Proclamacao da Republica
        '20-11', //Consciencia negra
        '25-12', //Natal
    ];


    public static function contarDiasUteis($date, $fim)
    {
        $mes = date('m', strtotime($date));
        $ano = date('Y', strtotime($date));
 
        $pascoa = date('Y-m-d', strtotime("$ano-03-21 + " . easter_days($ano) . " days"));
        $feriadoPascoa = date('d-m', strtotime($pascoa));
        $carnaval =  date('d-m', strtotime('-47 days', strtotime($pascoa)));
        $corpusChist = date('d-m', strtotime('+60 days', strtotime($pascoa)));
        $sextaFeiraSanta = date('d-m', strtotime('-2 days', strtotime($pascoa)));
        $feriadosVariaveis = array_merge(self::$feriados, [$sextaFeiraSanta, $feriadoPascoa, $carnaval, $corpusChist]);
        $dateAdded = date('Y-m-d', strtotime('+1 days', strtotime($date)));
        $_date = date('d-m', strtotime($date));
        if (date('m', strtotime($date)) == $mes) {
            if ((int)date('w', strtotime($date)) > 0 && (int)date('w', strtotime($date)) < 6) {
                if (!in_array($_date, $feriadosVariaveis)) {
                    self::$qnt++;
                }
                if ($date == $fim) {
                    return;
                }

                if ((int)date('m', strtotime('+1 days', strtotime($date))) == $mes) {
                    self::contarDiasUteis($dateAdded, $fim);
                }
            } elseif ((int)date('w', strtotime($date)) == 6 &&
                                                    (int)date('m', strtotime('+2 days', strtotime($date))) == $mes) {
                $dateAdded = date('Y-m-d', strtotime('+2 days', strtotime($date)));
                self::contarDiasUteis($dateAdded, $fim);
            } elseif ((int)date('w', strtotime($date)) == 0 &&
                                                    (int)date('m', strtotime('+1 days', strtotime($date))) == $mes) {
                $dateAdded = date('Y-m-d', strtotime('+1 days', strtotime($date)));
                self::contarDiasUteis($dateAdded, $fim);
            }
        }
    }
    
    public static function getDiasUteisMes($data, $fim)
    {
        self::$qnt = 0;
        self::contarDiasUteis($data, $fim);
        //echo " retorno: ",self::$qnt,exit();
        return self::$qnt;
    }
    
    public static function mesesReferencia()
    {
        return array(
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        );
    }

}