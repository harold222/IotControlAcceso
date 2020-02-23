#include <SPI.h>
#include <MFRC522.h>//libreria externa para controlar el rfid
#include <SoftwareSerial.h>

#define SS_PIN 10 //Pin 10 para el SS (SDA) del RC522
#define RST_PIN 9 //Pin 9 para el reset del RC522

SoftwareSerial mySerial(3, 4);
 
MFRC522 rfid(SS_PIN, RST_PIN); //Creamos el objeto para el RC522

MFRC522::MIFARE_Key key; 

byte nuidPICC[4];

void setup() { 
  Serial.begin(9600); //Iniciamos la comunicación  serial
  mySerial.begin(9600);
  SPI.begin(); //Iniciamos el Bus SPI
  rfid.PCD_Init(); // Iniciamos  el MFRC522 

  for (byte i = 0; i < 6; i++) {
    key.keyByte[i] = 0xFF;
  }

  Serial.println(F("This code scan the MIFARE Classsic NUID."));
  Serial.print(F("Using the following key:"));
  printHex(key.keyByte, MFRC522::MF_KEY_SIZE);
}
 
void loop() {
  // Revisamos si hay nuevas tarjetas  presentes
  if ( ! rfid.PICC_IsNewCardPresent())
    return;

  
  if ( ! rfid.PICC_ReadCardSerial())
    return;
    
  //Seleccionamos una tarjeta
  Serial.print(F("PICC type: "));
  MFRC522::PICC_Type piccType = rfid.PICC_GetType(rfid.uid.sak);
  Serial.println(rfid.PICC_GetTypeName(piccType));

  // Enviamos serialemente su UID
  if (piccType != MFRC522::PICC_TYPE_MIFARE_MINI &&  
    piccType != MFRC522::PICC_TYPE_MIFARE_1K &&
    piccType != MFRC522::PICC_TYPE_MIFARE_4K) {
    Serial.println(F("Your tag is not of type MIFARE Classic."));
    return;
  }


    Serial.println(F("A new card has been detected."));

    // Store NUID into nuidPICC array
    for (byte i = 0; i < 4; i++) {
      nuidPICC[i] = rfid.uid.uidByte[i];
    }
   
    Serial.println(F("The NUID tag is:"));
    Serial.print(F("In hex: "));
    printHex(rfid.uid.uidByte, rfid.uid.size);
    Serial.println();
    Serial.print(F("In dec: "));
    printDec(rfid.uid.uidByte, rfid.uid.size);
    Serial.println();
  


  // Terminamos la lectura de la tarjeta  actual
  rfid.PICC_HaltA();

  // detenemos encriptacion PCD
  rfid.PCD_StopCrypto1();
}

void printHex(byte *buffer, byte bufferSize) {
  for (byte i = 0; i < bufferSize; i++) {
    Serial.print(buffer[i] < 0x10 ? " 0" : " ");
    Serial.print(buffer[i], HEX);
  }
}

/**
 * impresion decimal del codigo.
 */
void printDec(byte *buffer, byte bufferSize) {
  
  for (byte i = 0; i < bufferSize; i++) {
    Serial.print(buffer[i] < 0x10 ? " 0" : "");
    Serial.print(buffer[i], DEC);
  }

  for (byte i = 0; i < bufferSize; i++) {//
    mySerial.print(buffer[i] < 0x10 ? " 0" : "");
    mySerial.print(buffer[i], DEC);
  }
  
  tone(6, 1000, 200);
  delay(2000);
}
