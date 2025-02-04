#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Servo.h>

// Define pins
const int trigPinOpen = D5;  // Sensor buka tutup
const int echoPinOpen = D6;
const int trigPinFull = D7;  // Sensor status penuh
const int echoPinFull = D8;
const int servoPin = D4;

// WiFi credentials
const char* ssid = "KISARA";
const char* password = "hitorigotou";
const char* serverUrl = "http://192.168.43.62:8000/api/trash-bin";

// Create Servo object
Servo servo;

// Variables
unsigned long lastDetectionTime = 0;
const unsigned long openDuration = 1000;  // 1 second
bool isLidOpen = false;
int fillPercentage = 0;
const int maxDepth = 20; // Kedalaman maksimal tempat sampah dalam cm

// Function to calculate distance
long getDistance(int trigPin, int echoPin) {
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);

    long duration = pulseIn(echoPin, HIGH);
    long distance = (duration * 0.034) / 2;
    return distance;
}

void setup() {
    Serial.begin(115200);
    pinMode(trigPinOpen, OUTPUT);
    pinMode(echoPinOpen, INPUT);
    pinMode(trigPinFull, OUTPUT);
    pinMode(echoPinFull, INPUT);

    servo.attach(servoPin);
    servo.write(0);  // Initial position (tutup)

    // Connect to WiFi
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Connecting to WiFi...");
    }
    Serial.println("Connected to WiFi");
}

void loop() {
    // Sensor buka tutup
    long distanceOpen = getDistance(trigPinOpen, echoPinOpen);
    Serial.print("Distance to open: ");
    Serial.println(distanceOpen);

    // Sensor status penuh
    long distanceFull = getDistance(trigPinFull, echoPinFull);
    Serial.print("Distance to full: ");
    Serial.println(distanceFull);

    // Hitung persentase keterisian
    fillPercentage = map(distanceFull, 0, maxDepth, 100, 0);
    fillPercentage = constrain(fillPercentage, 0, 100);

    Serial.print("Trash Bin Fill Percentage: ");
    Serial.print(fillPercentage);
    Serial.println("%");

    // Logika untuk buka tutup
    if (distanceOpen < 10) {  // Jika jarak < 10 cm
        lastDetectionTime = millis();
        if (!isLidOpen) {
            servo.write(180);  // Buka tutup
            isLidOpen = true;
        }
    }

    // Tutup otomatis setelah durasi tertentu
    if (isLidOpen && (millis() - lastDetectionTime > openDuration)) {
        servo.write(0);  // Tutup tutup
        isLidOpen = false;
    }

    // Kirim data ke server
    sendDataToServer(fillPercentage);

    delay(5000);  // Delay 5 detik sebelum update berikutnya
}

// Function to send data to server
void sendDataToServer(int fillPercent) {
    if (WiFi.status() == WL_CONNECTED) {
        WiFiClient client;  // Objek WiFiClient diperlukan untuk komunikasi HTTP
        HTTPClient http;
        http.begin(client, serverUrl); // Perbaikan penggunaan HTTPClient::begin

        http.addHeader("Content-Type", "application/x-www-form-urlencoded");

        String postData = "fill_percentage=" + String(fillPercent);
        int httpResponseCode = http.POST(postData);

        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);

        http.end();
    } else {
        Serial.println("WiFi not connected");
    }
}
