<?php

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {


  $pedidosQuery = $conn->query("SELECT * FROM pedidos;");

  $pedidos = $pedidosQuery->fetchAll();

  $pizzas = [];

  // Montando a pizza
  foreach ($pedidos as $pedido) {

    $pizza = [];

    //definir um array para a pizza
    $pizza["id"] = $pedido["id_pizza"];

    //resgatando a pizza
    $pizzaQuery = $conn->prepare("SELECT * FROM pizzas WHERE id = :id_pizza");

    $pizzaQuery->bindParam(":id_pizza", $pizza["id"]);

    $pizzaQuery->execute();

    $pizzaData = $pizzaQuery->fetch(PDO::FETCH_ASSOC);

    // resgatando a borda

    $bordaQuery = $conn->prepare("SELECT * FROM bordas WHERE id = :id_borda");

    $bordaQuery->bindParam(":id_borda", $pizzaData["id_borda"]);

    $bordaQuery->execute();

    $borda = $bordaQuery->fetch(PDO::FETCH_ASSOC);

    $pizza["borda"] = $borda["tipo"];

    // resgatando a massa

    $massaQuery = $conn->prepare("SELECT * FROM massas WHERE id = :id_massa");

    $massaQuery->bindParam(":id_massa", $pizzaData["id_massa"]);

    $massaQuery->execute();

    $massa = $massaQuery->fetch(PDO::FETCH_ASSOC);

    $pizza["massa"] = $massa["tipo"];

    // resgatando os sabores

    $saboresQuery = $conn->prepare("SELECT * FROM pizza_sabor WHERE id_pizza = :id_pizza");

    $saboresQuery->bindParam(":id_pizza", $pizza["id"]);

    $saboresQuery->execute();

    $sabores = $saboresQuery->fetchAll(PDO::FETCH_ASSOC);

    // resgatando o nome dos sabores

    $saboresDaPizza = [];

    $saborQuery = $conn->prepare("SELECT * FROM sabores WHERE id = :id_sabor");

    foreach ($sabores as $sabor) {
      $saborQuery->bindParam(":id_sabor", $sabor["id_sabor"]);

      $saborQuery->execute();

      $saborPizza = $saborQuery->fetch(PDO::FETCH_ASSOC);

      array_push($saboresDaPizza, $saborPizza["nome"]);
    }

    $pizza["sabores"] = $saboresDaPizza;

    //Resgatando Status do Pedido
    $pizza["status"] = $pedido["id_status"];

    //Adicionar array de pizza ao array de poizzas
    array_push($pizzas, $pizza);
  };

  $statusQuery = $conn->prepare("SELECT * FROM status;");

  $status = $statusQuery->fetchAll();
} else if ($method === "POST") {
};
