#bibliotecas
import requests
import time
import RPi.GPIO as GPIO
import datetime
import cv2
import bluepy.btle as bluepy

endURL = "http://10.79.11.247/ti/api/api.php?nome=led"

#Pinos
GPIO.setwarnings(False)
avoid_pin = 8
GPIO.setmode(GPIO.BOARD)  # Use physical pin numbering
GPIO.setup(3, GPIO.OUT, initial=GPIO.LOW)
GPIO.setup(avoid_pin, GPIO.IN)

#Strings para facilitar nos posts
String enviaNomeAvo = "avoid";
String enviaNomeHum = "semaforo";
String enviaNomeLum = "luminosidade";
String enviaNomeSem = "semaforo";
String enviaNomeLed = "led";
String enviaNomeBuz = "buzzer";

#variaveis para guardar os valores
float enviaAvoVal = 27;
float enviaHumVal = 29;
float enviaLumVal = 23;
float enviaBuzVal = 0;
float enviaLedVal = 1;
float enviaSemVal = 0;


print("--- Prima CTRL + C para terminar ---")

#funcao para fazer post
def post2API(nome, valor):
    agora = datetime.datetime.now()
    agora = agora.strftime("%d/%m/%y %H:%M:%S")
    conteudoPOST = {'nome': nome, 'valor': valor, 'hora': agora}
    r = requests.post('http://10.79.11.247/ti/api/api.php', data=conteudoPOST)
    if r.status_code == 200:
        print(r.text)
    else:
        print("Erro ao enviar o valor para a API!")

#codigo que estará sempre em loop a ler os valores do sensor, fazer posts/gets e quando detetar um dispositivo envia uma imagem
while True:
    try:
        time.sleep(1)
        enviaAvoVal = GPIO.input(avoid_pin)
        if enviaAvoVal == GPIO.LOW:
            print("Não detetou o comboio")
        else:
            print("Detetou o comboio")

        time.sleep(0.1)



        post2API(enviaNomeAvo, enviaAvoVal)
        post2API(enviaNomeSem, enviaAvoVal)

        conteudoAReceber = requests.get(endURL)
        if (conteudoAReceber.status_code == 200):
            valorRecebido = int((str(conteudoAReceber.content).replace("b'", "")).strip().replace("\\r\\n'", ""))
            data = time.localtime(time.time())
            print(valorRecebido)
            if valorRecebido == 1:
                # Ligar o LED na porta 2
                print("Vou ligar o LED do RPI\n")
                GPIO.output(3, 1)
            else:
                # Desligar o LED na porta 2
                print("Vou desligar o LED do RPI\n")
                GPIO.output(3, 0)


        else:
            print(
                "Erro a conectar com a API: " + str(conteudoAReceber.status_code) + " " + str(conteudoAReceber.reason))
            time.sleep(5)
            print("Próxima tentativa")



        class Procura(bluepy.DefaultDelegate):
            def handleDiscovery(self, dev, isNewDev, isNewData):
                if dev.rssi > -43:
                    print("Descobri um novo device:", dev.addr)
                    webcam_url = "https://rooftop.tryfail.net/image.jpeg"
                    capturaImagem = cv2.VideoCapture(webcam_url)
                    ret, frame = capturaImagem.read()
                    if(ret):
                        # Guarda a imagem capturada na diretoria atual deste ficheiro
                        cv2.imwrite("captura.jpg", frame)

                        # Abre o ficheiro "captura.jpg" com o nome imagem, dentro da variável "files"
                        files = {'imagem': open("captura.jpg", "rb")}
                        # Envia o conteúdo do ficheiro (imagem) pela API para a plataforma
                        pedidoPost = requests.post("http://10.79.11.247/ti/api/api.php", files=files)

                        if pedidoPost.status_code == 200:
                            print("Foto guardada na plataforma com sucesso !")
                        else :
                            print("Erro ao guardar foto na plataforma !")
                            print("\n" + str(pedidoPost.status_code))

                    capturaImagem.release()

        scanner = bluepy.Scanner().withDelegate(Procura())
        devices = scanner.scan(10, passive=True)
    except KeyboardInterrupt as keyboard:
        GPIO.cleanup()
        print("\n\nO programa foi interrompido pelo utilizador !")
        print("\nA terminar...\n")
        break
