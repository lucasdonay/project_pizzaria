<?php

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

//RESGATE DOS DADOS, MONTAGEM DO PEDIDO
if ($method === "GET") {

  $bordasQuery = $conn->query("SELECT * FROM bordas;");

  $bordas = $bordasQuery->fetchAll();

  $massasQuery = $conn->query("SELECT * FROM massas;");

  $massas = $massasQuery->fetchAll();

  $saboresQuery = $conn->query("SELECT * FROM sabores;");

  $sabores = $saboresQuery->fetchAll();
} else if ($method === "POST") {
}
