<!doctype html>
<html lang="pt-pt">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cidade Inteligente - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<body id="dashboard"> <!--  id da dashboard -->


  <?php
  //variavel que guarda se é user ou admin
  //se nao usar um utilizador (isto é se nao houver sessao), e volta a pagina de login
  include("VerificarSessao.php");

  //le os dados dos sensores e atuadores do ficheiro e guarda numa variavel
  $temperatura_valor = file_get_contents("api/files/temperatura/valor.txt");
  $temperatura_hora = file_get_contents("api/files/temperatura/hora.txt");
  $temperatura_nome = file_get_contents("api/files/temperatura/nome.txt");

  $humidade_valor = file_get_contents("api/files/humidade/valor.txt");
  $humidade_hora = file_get_contents("api/files/humidade/hora.txt");
  $humidade_nome = file_get_contents("api/files/humidade/nome.txt");

  $avoid_valor = file_get_contents("api/files/avoid/valor.txt");
  $avoid_hora = file_get_contents("api/files/avoid/hora.txt");
  $avoid_nome = file_get_contents("api/files/avoid/nome.txt");


  $semaforo_valor = file_get_contents("api/files/semaforo/valor.txt");
  $semaforo_hora = file_get_contents("api/files/semaforo/hora.txt");
  $semaforo_nome = file_get_contents("api/files/semaforo/nome.txt");

  $alarme_valor = (file_get_contents("api/files/alarme/valor.txt"));
  $alarme_hora = file_get_contents("api/files/alarme/hora.txt");
  $alarme_nome = file_get_contents("api/files/alarme/nome.txt");

  $sensorLuminosidade_valor = file_get_contents("api/files/luminosidade/valor.txt");
  $sensorLuminosidade_hora = file_get_contents("api/files/luminosidade/hora.txt");
  $sensorLuminosidade_nome = file_get_contents("api/files/luminosidade/nome.txt");

  $led_valor = file_get_contents("api/files/led/valor.txt");
  $led_hora = file_get_contents("api/files/led/hora.txt");
  $led_nome = file_get_contents("api/files/led/nome.txt");

  $ledsRGB_valor = file_get_contents("api/files/semaforo/valor.txt");
  ?>


  <!-- Criação da navbar-->
  <?php include("navbar.php"); ?>


  <div class="container">
    <div class="card">
      <div class="card-body">
        <img src="imagens/estg.png" alt="IPL" style="width: 300px;" class="d-inline float-end">
        <h1>
          Cidade Inteligente
        </h1>
        <p>Bem-vindo(a) <b style="font-size: 20px;">&nbsp;<?= $_SESSION['username'] ?></b></p>

        <small class="card-text">Tecnologias de Internet - Engenharia Informática</small>
      </div>
    </div>
  </div>
<!-- Criação dos cards, em que atualiza com o ultimo valor e hora-->
  <br> <br>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div class="card text-center">
          <div class="card-header sensor" style="background: linear-gradient(90deg, #FFDA33, #FF9F33, #FF5533);"><b>Temperatura</b></div>
          <div class="card-body"><img id="imagemTemperatura" src="imagens/temperature-high.png" alt=""></div>
          <!-- ----- Mostra o butão do histórico caso o utilizador seja um admin ----->
          <div id="textoTemperatura" class="card-footer"><b>Atualização: </b><?php echo $temperatura_hora;
                                                                              $mensagem = sprintf("<strong>%s°C</strong>", $temperatura_valor);
                                                                              echo $mensagem; ?></div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="card text-center">
          <div class="card-header sensor" style="background: linear-gradient(90deg, #5EC7FE, #2A9AFC, #0548EC);"><b>Humidade</b></div>
          <div class="card-body"><img id="imagemHumidade" src="imagens/humidity-high.png" alt=""></div>
          <div id="textoHumidade" class="card-footer"><b>Atualização: </b><?php echo $humidade_hora;
                                                                          $mensagem = sprintf("<strong>%s%%</strong>", $humidade_valor);
                                                                          echo $mensagem; ?></div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="card text-center">
          <div class="card-header atuador" style="background: linear-gradient(90deg, #FFFFFF, #FDFCB3, #F9F741);"><b>LED</b></div>
          <div class="card-body">

            <?php //alternar a imagem consoante o estado do led
            $led5 = "ON";
            if (str_contains($led_valor, $led5)) {
              echo '<img id="imagemLED" src="imagens/light-on.png" class="rating" title="Fresh" alt="Fresh"/>';
            } else {
              echo '<img id="imagemLED" src="imagens/light-off.png" class="rating" title="Rotten" alt="Rotten" />';
            } ?>
          </div>
          <div id="textoLED" class="card-footer">
            <b>Atualização: </b><?php echo $led_hora; ?>
            <?php
            $mensagem = "<strong>" . $led_valor . "</strong>";
            echo $mensagem;
            ?>
          </div>
        </div>
      </div>

      <div class="col-sm-4 mt-3">
        <div class="card text-center">
          <div class="card-header atuador" style="background: linear-gradient(90deg, #ee7752, #e73c7e);"><b><?= $semaforo_nome ?></b></div>
          <div class="card-body"><?php if (intval(trim($semaforo_valor)) == 1) {
                                    echo "<img alt='imagem' id='imagemSemaforo' src='imagens/semaforo_verde.png'>";
                                  } else {
                                    echo "<img alt='imagem' id='imagemSemaforo' src='imagens/semaforo_vermelho.png'>";
                                  } ?></div>
          <div class="card-footer"><b>Atualização: </b><?php echo $semaforo_hora ?></div>
        </div>
      </div>

      <div class="col-sm-4 mt-3">
        <div class="card text-center">
          <div class="card-header atuador" style="background: linear-gradient(90deg, #23a6d5, #23d5ab);"><b><?= $alarme_nome ?></b></div>
          <div class="card-body"><?php if ($alarme_valor == 1) {
                                    echo "<img alt='imagem' id='imagemBuzzer' src='imagens/buzzer_on.png'>";
                                  } else {
                                    echo "<img id='imagemBuzzer' alt='imagem' src='imagens/buzzer_off.png'>";
                                  } ?></div>
          <div class="card-footer"><b>Atualização: </b><?php echo $alarme_hora ?></div>
        </div>
      </div>

                                <!-- Ocultar a imagem do segurança dos utilizadores -->
      <div class="col-sm-4 mt-3" <?php if ($_SESSION['permissoes'] == "user") {
                                    echo 'style="visibility: hidden"';
                                  } ?>>
        <div class="card text-center">
          <div class="card-header atuador" style="background: linear-gradient(90deg, #EFFC84, #E3FA29);"><b>Último Segurança</b></div>
          <?php echo "<img alt='imagem' src='api/imagens/webcam.jpg?id=" . time() . "' style='width: 100%; height: 300px; object-fit: cover;'>"; ?>
          <div class="card-footer"><b>Atualização: </b><?php echo $led_hora ?></div>
        </div>
      </div>

    </div>
  </div>

<!-- criação da tabela com os valores atualizados -->
  <br> <br> <br>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <b>Tabela de Sensores</b>
      </div>
      <div class="card-body">

        <table class="table table-striped  ">
          <thead>
            <tr>
              <th scope="col">Tipo de Dispositivo IoT</th>
              <th scope="col">Valor</th>
              <th scope="col">Data de Atualização</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= $temperatura_nome ?></td>
              <td id="valor_temperatura"><?= $temperatura_valor ?>º</td>
              <td id="hora_temperatura"><?= $temperatura_hora ?></td>
            </tr>
            <tr>
              <td><?= $humidade_nome ?></td>
              <td id="valor_humidade"><?= $humidade_valor ?>%</td>
              <td id="hora_humidade"><?= $humidade_hora ?></td>
            </tr>
            <tr>
              <td><?= $led_nome ?></td>
              <td id="valor_led"><?= $led_valor ?></td>
              <td id="hora_led"><?= $led_hora ?></td>
            </tr>
            <tr>
              <td><?= $avoid_nome ?></td>
              <td id="valor_avoid"><?= $avoid_valor ?></td>
              <td id="hora_avoid"><?= $avoid_hora ?></td>
            </tr>
            <tr>
              <td><?= $sensorLuminosidade_nome ?></td>
              <td id="valor_sensorLuminosidade"><?= $sensorLuminosidade_valor ?></td>
              <td id="hora_sensorLuminosidade"><?= $sensorLuminosidade_hora ?></td>
            </tr>
            <tr>
              <td><?= $semaforo_nome ?></td>
              <td id="valor_semaforo"><?= $semaforo_valor ?></td>
              <td id="hora_semaforo"><?= $semaforo_hora ?></td>
            </tr>
            <tr>
              <td><?= $alarme_nome ?></td>
              <td id="valor_alarme"><?= $alarme_valor ?></td>
              <td id="hora_alarme"><?= $alarme_hora ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="fundooo">
    <!--  Contêiner principal -->
    <div class="d-flex justify-content-center encaixe">
      <!--  Contêiner para o vídeo da cidade -->
      <div class="video-container">
        <!-- <img id="vdcity" src="imagens/city_view.png" alt="Imagem da cidade"> -->
        <video id="vdcity" src="imagens/cidade.mp4"></video>
      </div>
      <!--  Overlay para simular dia e noite -->
      <div id="fundoCidade" class="overlay" style="<?php if (intval(trim($sensorLuminosidade_valor)) == 1) {
                                                      echo "opacity: 0.0";
                                                    } else {
                                                      echo "opacity: 0.5";
                                                    } ?>"></div>
      <!--  Imagem do LED  -->
      <img id="ledimage1" class="imagemLED" src="imagens/luz.png" alt="Imagem de overlay">
      <img id="ledimage2" class="imagemLED" src="imagens/luz.png" alt="Imagem de overlay">
      <img id="ledimage3" class="imagemLED" src="imagens/luz.png" alt="Imagem de overlay">

      <img id="buzzerimage1" class="imagemLED" src="<?php if (intval(trim($alarme_valor)) == 1) {
                                                      echo "imagens/buzzerMapa_on.jpg";
                                                    } else {
                                                      echo "imagens/buzzerMapa_off.png";
                                                    } ?>" alt="Imagem de overlay">

      <!--  Texto do LED-->
      <img id="sensor_us" class="sensores" src="imagens/sensor_us_n.png" alt="Imagem de overlay">
      <img id="semaforo" class="imagemLED" src="imagens/semaforo_g.png" alt="Imagem de overlay">

      <span id="textLED1" class="textled"><?= $led_valor; ?></span>
      <span id="textLED2" class="textled"><?= $led_valor; ?></span>
      <span id="textLED3" class="textled"><?= $led_valor; ?></span>
      <span id="textBuzzer1" class="textled"><?= $alarme_valor; ?></span>
      <span id="textSensor_us" class="textled"><?= $alarme_valor ?></span>
      <span id="textsemaforo" class="textled"><?= $ledsRGB_valor ?></span>
      <!--  Contêiner para os botões -->
      <div class="botoesVER1" <?php if ($_SESSION['permissoes'] == "user") {
                                echo 'style="visibility: hidden"';
                              } ?>>
        <div class="dropdown5">
          <button class="btn btn-primary" style="width: 150px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">Verificar</button>
          <div class="dropdown5-content">
            <button id="verificaLEDS" class="button-85" onclick="toggleVerificacaoLEDS()">Iluminação</button>
            <button id="playButton" class="button-85">Iniciar-Vídeo</button>
            <button id="verificaSensor" class="button-85" onclick="toggleVerificacaoSensor()">Sensor</button>
            <button id="verificaSemaforo" class="button-85" onclick="toggleVerificacaoSemaforo()">Semaforo</button>
            <button id="verificaAlarme" class="button-85" onclick="toggleVerificacaoAlarme()">Alarme</button>
          </div>
        </div>
      </div>
      <div class="botoesVER2" <?php if ($_SESSION['permissoes'] == "user") {
                                echo 'style="visibility: hidden"';
                              } ?>>
        <div class="dropdown5">
          <button class="btn btn-primary" style="width: 150px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">Atuar</button>
          <div class="dropdown5-content">
            <input type="hidden" name="nome" id="nomeLED" value="led">
            <input type="hidden" name="LEDSvalor" id="LEDSvalorID" value="<?php
                                                                          if ($led_valor == "" || $led_valor == NULL || $led_valor == PHP_EOL) {
                                                                            echo "OFF";
                                                                          } else {
                                                                            echo $led_valor;
                                                                          } ?>"> <!-- ---- Input para enviar valor para a API----->
            <button id="ligaLEDS" class="button-85" onclick="AlterarValorButaoLED()"><?php if (str_contains($led_valor, "OFF") || $led_valor == "" || $led_valor == NULL || $led_valor == PHP_EOL) {
                                                                                        echo "Ligar ";
                                                                                      } else {
                                                                                        echo "Desligar ";
                                                                                      } ?>LEDS</button>
            <input type="hidden" name="nome" id="nomeAlarme" value="alarme">
            <input type="hidden" name="Alarmevalor" id="AlarmevalorID" value="<?php
                                                                              if ($alarme_valor == "" || $alarme_valor == NULL || $alarme_valor == PHP_EOL) {
                                                                                echo "0";
                                                                              } else {
                                                                                echo $alarme_valor;
                                                                              } ?>"> <!-- ---- Input para enviar valor para a API----->
            <button id="ativaBuzzer" class="button-85" onclick="AlterarValorButaoAlarme()"><?php if ($alarme_valor == "0") {
                                                                                              echo "Ligar ";
                                                                                            } else {
                                                                                              echo "Desligar ";
                                                                                            } ?>Alarme</button>
            <input type="hidden" name="nome" id="nomeSemaforo" value="semaforo">
            <input type="hidden" name="valor" id="SemaforovalorID" value="<?php
                                                                          if ($ledsRGB_valor == "" || $ledsRGB_valor == NULL || $ledsRGB_valor == PHP_EOL) {
                                                                            echo "0";
                                                                          } else {
                                                                            echo $ledsRGB_valor;
                                                                          } ?>"> <!-- ---- Input para enviar valor para a API----->
            <button id="ligaSemaforo" type="button" class="button-85" onclick="AlterarValorButaoSemaforo()"><?php if ($ledsRGB_valor == "1") {
                                                                                                              echo "Vermelho ";
                                                                                                            } else {
                                                                                                              echo "Verde ";
                                                                                                            } ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <span class="textotemp">Temperatura nos Últimos Dias</span>
  <div class="grafico">
    <canvas id="myChart"></canvas>
  </div>






  <script src="alterarValorButao.js"></script>

  <script>
    var sensor_us = document.getElementById('valor_avoid').value;


    var isFunctionRunning = false;

    function checkSensorValue() {
      if (isFunctionRunning) {
        return; // A função já está em execução, não faz nada
      }

      isFunctionRunning = true; // Marca a função como em execução
      var sair = 0;


      if (sensor_us == 1) {
        document.getElementById("textSensor_us").textContent = "1";
        //document.getElementById("sensor_us").src = "imagens/sensor_us_s.png";
        document.getElementById("textsemaforo").textContent = "Red";
        document.getElementById("semaforo").src = "imagens/semaforo_r.png";

        setTimeout(function() {
          sensor_us = 0;
          isFunctionRunning = false; // Marca a função como concluída
        }, 3000);

      } else {
        document.getElementById("textSensor_us").textContent = "0";
        //document.getElementById("sensor_us").src = "imagens/sensor_us_n.png";
        setTimeout(function() {
          document.getElementById("textsemaforo").textContent = "Green";
          document.getElementById("semaforo").src = "imagens/semaforo_g.png";
          // Outras ações que você deseja realizar após o atraso de 2 segundos

          isFunctionRunning = false; // Marca a função como concluída
        }, 3000);

      }

    }


    // Event listener para o botão de reprodução do vídeo
    document.getElementById('playButton').addEventListener('click', function() {
      sensor_us = 1;
      var video = document.getElementById('vdcity');
      video.style.display = 'block'; // Mostra o vídeo
      video.play(); // Inicia a reprodução do vídeo
    });

    // Chama a função checkSensorValue a cada segundo

    var verificacaoAtiva = false;

    // Função para alternar a verificação dos LEDs
    function toggleVerificacaoLEDS() {
      var textLED1 = document.getElementById("textLED1");
      var textLED2 = document.getElementById("textLED2");
      var textLED3 = document.getElementById("textLED3");
      var button = document.getElementById("verificaLEDS");

      if (verificacaoAtiva) {
        textLED1.style.opacity = "0";
        textLED2.style.opacity = "0";
        textLED3.style.opacity = "0";
        button.textContent = "Iluminação";
        verificacaoAtiva = false;
      } else {
        textLED1.style.opacity = "1";
        textLED2.style.opacity = "1";
        textLED3.style.opacity = "1";
        button.textContent = "Parar";
        verificacaoAtiva = true;
      }
    }

    function toggleVerificacaoSemaforo() {
      var textSemaforo = document.getElementById("textsemaforo");
      var button = document.getElementById("verificaSemaforo");

      if (verificacaoAtiva) {
        textSemaforo.style.opacity = "0";
        button.textContent = "Semaforo";
        verificacaoAtiva = false;
      } else {
        textSemaforo.style.opacity = "1";
        button.textContent = "Parar";
        verificacaoAtiva = true;
      }
    }

    function toggleVerificacaoSensor() {
      var textSensor = document.getElementById("textSensor_us");
      var button = document.getElementById("verificaSensor");

      if (verificacaoAtiva) {
        textSensor.style.opacity = "0";
        button.textContent = "Sensor";
        verificacaoAtiva = false;
      } else {
        textSensor.style.opacity = "1";
        button.textContent = "Parar";
        verificacaoAtiva = true;
      }
    }

    function toggleVerificacaoAlarme() {
      var textAlarme = document.getElementById("textBuzzer1");
      var button = document.getElementById("verificaAlarme");

      if (verificacaoAtiva) {
        textAlarme.style.opacity = "0";
        button.textContent = "Alarme";
        verificacaoAtiva = false;
      } else {
        textAlarme.style.opacity = "1";
        button.textContent = "Parar";
        verificacaoAtiva = true;
      }
    }
  </script>

  <?php
  // Ler o conteúdo do arquivo em um array
  $linhas = file('api/files/temperatura/log.txt');

  // Obter as últimas 5 linhas
  $ultimasLinhas = array_slice($linhas, -5);

  // Criar um vetor com os últimos 5 valores
  $ultimosValores = array();
  foreach ($ultimasLinhas as $linha) {
    $dados = explode(';', $linha);
    if (isset($dados[1])) {
      $valor = trim($dados[1]);
      $ultimosValores[] = $valor;
    }
  }

  ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var graficoDiv = document.querySelector('.grafico');
      var ctx = graficoDiv.querySelector('#myChart').getContext('2d');

      var ultimosValores = <?php echo json_encode($ultimosValores); ?>;

      var gradient = ctx.createLinearGradient(0, 0, 0, 200);
      gradient.addColorStop(0, '#FF0000'); // Vermelho
      gradient.addColorStop(0.1, '#FF0000'); // Laranja
      gradient.addColorStop(0.2, '#FF0000'); // Laranja claro
      gradient.addColorStop(0.3, '#FF0000'); // Amarelo escuro
      gradient.addColorStop(0.4, '#FF0000'); // Amarelo
      gradient.addColorStop(0.5, '#FF8000'); // Amarelo claro
      gradient.addColorStop(0.6, '#FFFF00'); // Verde claro
      gradient.addColorStop(0.7, '#FFFF00'); // Verde
      gradient.addColorStop(0.8, '#FFFF00'); // Verde claro
      gradient.addColorStop(1, '#40FF00'); // Verde claro




      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Sexta', 'Sábado', 'Domingo', 'Ontem', 'Hoje'],
          datasets: [{
            label: 'Temperatura',
            data: ultimosValores,
            backgroundColor: gradient,
            borderColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 0
          }]
        },
        options: {
          plugins: {
            gradientify: {
              gradients: [{
                id: 'myGradient',
                angle: Math.PI / 2,
                colors: "#000", // Cor da label
              }]
            },
            tooltip: {
              // Configurações do tooltip
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value, index, values) {
                  return value + '°C';
                },
                color: '#000', // Cor do texto do eixo y
                font: {
                  size: 14 // Tamanho do texto do eixo y
                }
              }
            },
            x: {
              ticks: {
                color: '#000', // Cor do texto do eixo x
                font: {
                  size: 14 // Tamanho do texto do eixo x
                }
              }
            }
          },
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              labels: {
                color: "#000", // Cor da label
                font: {
                  size: 20 // Tamanho da fonte da label
                }
              }
            }
          }
        }
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>
