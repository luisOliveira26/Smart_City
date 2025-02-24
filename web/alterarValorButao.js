//esta funcao chama a funcao "ObterValoresGET()" 1 em 1 seg que depois vai chamar todas as outras funcoes
setInterval(ObterValoresGET, 1000);
//As funções de "AlterarValorButao..." verificam o estado atual do valor do respetivo sensor/atuador e fazem um POST do valor simétrico para a API  
function AlterarValorButaoSemaforo() { 
    var nomeSemaforo = document.getElementById("nomeSemaforo");
    var conteudoSemaforo = document.getElementById("SemaforovalorID");
    var valorSemaforo = conteudoSemaforo.value;

    if (Number(valorSemaforo) == 1) {
        valorSemaforo = 0;
    } else if (Number(valorSemaforo) == 0) {
        valorSemaforo = 1;
    }
    var data = new Date();
    valorDataAtual = data.getFullYear() + "/" + data.getMonth() + "/" + data.getDate() + " " + data.getHours() + ":" + data.getMinutes() + ":" + data.getSeconds();
    var url = "http://127.0.0.1/ti/api/api.php";

    const informacao = new URLSearchParams();
    informacao.append('nome', nomeSemaforo.value);
    informacao.append('valor', valorSemaforo);
    informacao.append('hora', valorDataAtual.toString());

    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/x-www-form-urlencoded',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: informacao.toString(),
    });
}

function AlterarValorButaoLED() {
    var nomeLED = document.getElementById("nomeLED");
    var conteudoLED = document.getElementById("LEDSvalorID");
    var valorLED = conteudoLED.value;

    if (valorLED.includes("ON")) {
        valorLED = "OFF";
    } else if (valorLED.includes("OFF")) {
        valorLED = "ON";
    }
    var data = new Date();
    valorDataAtual = data.getFullYear() + "/" + data.getMonth() + "/" + data.getDate() + " " + data.getHours() + ":" + data.getMinutes() + ":" + data.getSeconds();
    var url = "http://127.0.0.1/ti/api/api.php";

    const informacao = new URLSearchParams();
    informacao.append('nome', nomeLED.value);
    informacao.append('valor', valorLED);
    informacao.append('hora', valorDataAtual.toString());

    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/x-www-form-urlencoded',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: informacao.toString(),
    });
}

function AlterarValorButaoAlarme() {
    var nomeAlarme = document.getElementById("nomeAlarme");
    var conteudoAlarme = document.getElementById("AlarmevalorID");
    var valorAlarme = conteudoAlarme.value;

    if (Number(valorAlarme) == 1) {
        valorAlarme = 0;
    } else if (Number(valorAlarme) == 0) {
        valorAlarme = 1;
    }
    var data = new Date();
    valorDataAtual = data.getFullYear() + "/" + data.getMonth() + "/" + data.getDate() + " " + data.getHours() + ":" + data.getMinutes() + ":" + data.getSeconds();
    var url = "http://127.0.0.1/ti/api/api.php";

    const informacao = new URLSearchParams();
    informacao.append('nome', nomeAlarme.value);
    informacao.append('valor', valorAlarme);
    informacao.append('hora', valorDataAtual.toString())

    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/x-www-form-urlencoded',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: informacao.toString(),
    });
}
// esta funcao "ObterValoresGET()"" chama as seguintes funcoes 
function ObterValoresGET() {
    verificarDadosTemperatura();
    verificarDadosHumidade();
    verificarDadosLED();
    verificarDadosSemaforo();
    verificarDadosAlarme();
    verificarDadosAvoid();
    verificarDadosLuminosidade();
}


// Todas as funcoes "verificarDados..." verificam se o valor na dashboard é igual ao valor da API e em caso contrário atualizam os respetivos campos para atualizar o mesmo
function verificarDadosTemperatura() {

    fetch('http://127.0.0.1/ti/api/getHora.php?nome=temperatura').then(response => response.text()).then(temperatura2 => {
        document.getElementById('hora_temperatura').innerHTML = temperatura2;
        document.getElementById('textoTemperatura').innerHTML = "<b>Atualização: </b>" + temperatura2;
    })

    fetch('http://127.0.0.1/ti/api/api.php?nome=temperatura').then(response => response.text()).then(temperatura => {
        document.getElementById('valor_temperatura').innerHTML = temperatura + ' º';
        document.getElementById('textoTemperatura').innerHTML = document.getElementById('textoTemperatura').innerHTML + "<strong>"+temperatura +" °C</strong>";
        if(parseInt(temperatura.trim()) > 25){
            document.getElementById('imagemTemperatura').src = "imagens/temperature-high.png";
        }else{
            document.getElementById('imagemTemperatura').src = "imagens/temperature-low.png"; 
        }
    
    })

}

function verificarDadosHumidade() {
    fetch('http://127.0.0.1/ti/api/getHora.php?nome=humidade').then(response => response.text()).then(humidade2 => {
        document.getElementById('hora_humidade').innerHTML = humidade2;
        document.getElementById('textoHumidade').innerHTML = "<b>Atualização: </b>" + humidade2;
    })
    fetch('http://127.0.0.1/ti/api/api.php?nome=humidade').then(response => response.text()).then(humidade => {
        document.getElementById('valor_humidade').innerHTML = humidade + ' %';
        document.getElementById('textoHumidade').innerHTML = document.getElementById('textoHumidade').innerHTML + "<strong>"+humidade +" %</strong>";
        if(parseInt(humidade.trim()) > 50 && !(humidade.includes("nan"))){
            document.getElementById('imagemHumidade').src = "imagens/humidity-high.png";
        }else{
            document.getElementById('imagemHumidade').src = "imagens/humidity-low.png"; 
        }
    })
}

function verificarDadosLED() {
    var String1;

    fetch('http://127.0.0.1/ti/api/getHora.php?nome=led').then(response => response.text()).then(led => {
        document.getElementById('hora_led').innerHTML = led;
        String1 = "<b>Atualização: </b>" + led;
    })

    fetch('http://127.0.0.1/ti/api/api.php?nome=led').then(response => response.text()).then(led => {
        if (!((document.getElementById('LEDSvalorID').value).includes(led.trim()))) {
            document.getElementById('valor_led').innerHTML = led;
            document.getElementById('textoLED').innerHTML = String1 + "<strong>"+led +"</strong>";
            document.getElementById('LEDSvalorID').value = led;
            document.getElementById('textLED1').innerHTML = led;
            document.getElementById('textLED2').innerHTML = led;
            document.getElementById('textLED3').innerHTML = led;
            var valorLED = led;
            if (valorLED.includes("ON")) {
                document.getElementById('ligaLEDS').innerHTML = "Desligar LEDS";
                document.getElementById('imagemLED').src = "imagens/light-on.png";
                document.getElementById('imagemLED').alt = "Fresh";
                document.getElementById("imagemLED").title = "Fresh";
            }
            if (valorLED.includes("OFF")) {
                document.getElementById('ligaLEDS').innerHTML = "Ligar LEDS";
                document.getElementById('imagemLED').src = "imagens/light-off.png";
                document.getElementById('imagemLED').alt = "Rotten";
                document.getElementById("imagemLED").title = "Rotten";
            }
        }
    })



}

function verificarDadosSemaforo() {
    fetch('http://127.0.0.1/ti/api/api.php?nome=semaforo').then(response => response.text()).then(semaforo => {
        if (!(document.getElementById('SemaforovalorID').value).includes(semaforo.trim())) {
            document.getElementById('SemaforovalorID').value = semaforo.trim();
            document.getElementById('valor_semaforo').innerHTML = semaforo.trim();
            document.getElementById("textsemaforo").innerHTML = semaforo.trim();
            var valorSemaforo = semaforo.trim();
            if (valorSemaforo.includes("1")) {
                document.getElementById('ligaSemaforo').innerHTML = "Vermelho";
                document.getElementById("semaforo").src = "imagens/semaforo_g.png";
                document.getElementById("imagemSemaforo").src = "imagens/semaforo_verde.png";
            }
            else{
                document.getElementById('ligaSemaforo').innerHTML = "Verde";
                document.getElementById("semaforo").src = "imagens/semaforo_r.png";
                document.getElementById("imagemSemaforo").src = "imagens/semaforo_vermelho.png";
            }
        }
    })

    fetch('http://127.0.0.1/ti/api/getHora.php?nome=semaforo').then(response => response.text()).then(semaforo2 => {
        document.getElementById('hora_semaforo').innerHTML = semaforo2;
    })
}

function verificarDadosAlarme() {
    fetch('http://127.0.0.1/ti/api/api.php?nome=alarme').then(response => response.text()).then(alarme => {
        if (!(document.getElementById('AlarmevalorID').value).includes(alarme.trim())) {
            document.getElementById('AlarmevalorID').value = alarme.trim();
            document.getElementById('valor_alarme').innerHTML = alarme.trim();
            var valorAlarme = alarme.trim();
            document.getElementById('textSensor_us').innerHTML = alarme.trim();
            if (valorAlarme.includes("0")) {
                document.getElementById('ativaBuzzer').innerHTML = "Ligar Alarme";
                document.getElementById("imagemBuzzer").src = "imagens/buzzer_off.png";
                document.getElementById("buzzerimage1").src = "imagens/buzzerMapa_off.png";
                document.getElementById('textBuzzer1').innerHTML = "0";
            }
            if (valorAlarme.includes("1")) {
                document.getElementById('ativaBuzzer').innerHTML = "Desligar Alarme";
                document.getElementById("imagemBuzzer").src = "imagens/buzzer_on.png";
                document.getElementById("buzzerimage1").src = "imagens/buzzerMapa_on.jpg";
                document.getElementById('textBuzzer1').innerHTML = "1";
            }
        }
    })

    fetch('http://127.0.0.1/ti/api/getHora.php?nome=alarme').then(response => response.text()).then(alarme2 => {
        document.getElementById('hora_alarme').innerHTML = alarme2;
    })
}

function verificarDadosAvoid() {
    fetch('http://127.0.0.1/ti/api/api.php?nome=avoid').then(response => response.text()).then(avoid => {
        document.getElementById('valor_avoid').innerHTML = avoid;
        if(Number(avoid.trim()) == 1){
            document.getElementById("sensor_us").src = "imagens/sensor_us_s.png";
        }else{
            document.getElementById("sensor_us").src = "imagens/sensor_us_n.png";
        }
    })

    fetch('http://127.0.0.1/ti/api/getHora.php?nome=avoid').then(response => response.text()).then(avoid2 => {
        document.getElementById('hora_avoid').innerHTML = avoid2;
    })
}

function verificarDadosLuminosidade() {
    fetch('http://127.0.0.1/ti/api/api.php?nome=luminosidade').then(response => response.text()).then(luminosidade => {
        document.getElementById('valor_sensorLuminosidade').innerHTML = luminosidade;
        if(Number(luminosidade.trim()) == 1){
            document.getElementById('fundoCidade').style.opacity = 0.0;
        }else{
            document.getElementById('fundoCidade').style.opacity = 0.5;
        }
    
    })


    fetch('http://127.0.0.1/ti/api/getHora.php?nome=luminosidade').then(response => response.text()).then(luminosidade2 => {
        document.getElementById('hora_sensorLuminosidade').innerHTML = luminosidade2;
    })
}


