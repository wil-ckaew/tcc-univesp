<?php
// Conexão com o banco de dados (substitua com suas configurações)
$host = "localhost";
$usuario = "energizz_jd";
$senha = "Tucg9167";
$banco = "energizz_pi5";

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
$patientID = $_POST["patientID"];
// Recebe os dados dos sensores
$temperaturaBME = $_POST["temperaturaBME"];
$umidadeBME = $_POST["umidadeBME"];
$pressaoBME = $_POST["pressaoBME"];
$spo2 = $_POST["spo2"];
$bpm = $_POST["bpm"];
$temperaturaDS18B20 = $_POST["temperaturaDS18B20"];

// Insere os dados nas tabelas correspondentes
$sql1 = "INSERT INTO dados_sensor1 (temperatura, umidade, pressao, paciente_id) VALUES (?, ?, ?, ?)";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ddds", $temperaturaBME, $umidadeBME, $pressaoBME,$patientID);

$sql2 = "INSERT INTO dados_sensor2 (spo2, bpm, paciente_id) VALUES (?, ?, ?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("dds", $spo2, $bpm,$patientID );

$sql3 = "INSERT INTO dados_sensor3 (temperatura, paciente_id) VALUES (?, ?)";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("ds", $temperaturaDS18B20,$patientID);

$response = [];

if ($stmt1->execute()) {
    $response["messageBME"] = "Medição do BME280 inserida com sucesso!";
} else {
    $response["messageBME"] = "Erro ao inserir medição do BME280: " . $stmt1->error;
}

if ($stmt2->execute()) {
    $response["messageMAX30102"] = "Medição do MAX30102 inserida com sucesso!";
} else {
    $response["messageMAX30102"] = "Erro ao inserir medição do MAX30102: " . $stmt2->error;
}

if ($stmt3->execute()) {
    $response["messageDS18B20"] = "Medição do DS18B20 inserida com sucesso!";
} else {
    $response["messageDS18B20"] = "Erro ao inserir medição do DS18B20: " . $stmt3->error;
}

$stmt1->close();
$stmt2->close();
$stmt3->close();
$conn->close();

echo json_encode($response);
?>
