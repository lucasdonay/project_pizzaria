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

    $data = $_POST;

    $borda = $data["borda"];
    $massa = $data["massa"];
    $sabores = $data["sabores"];

    //if de validacao de sabores maximos
    if (count($sabores) > 3) {
        $_SESSION["msg"] = "Selecione no maximo 3 sabores!";
        $_SESSION["status"] = "warning";
    } else {

        //salvando borda e massa na pizza

        $stmt = $conn->prepare("INSERT INTO pizzas (id_borda, id_massa) VALUES (:borda, :massa)");


        // filtrando inputs

        $stmt->bindParam(":borda", $borda, PDO::PARAM_INT);
        $stmt->bindParam(":massa", $massa, PDO::PARAM_INT);

        $stmt->execute();

        //resgatando ultimo dia da pizza

        $pizzaId = $conn->lastInsertId();
        $stmt = $conn->prepare("INSERT INTO pizza_sabor (id_pizza, id_sabor) VALUES (:pizza, :sabor)");


        // repetição até terminar de salvar todos os sabores

        foreach ($sabores as $sabor) {
            //filtrando os inputs

            $stmt->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
            $stmt->bindParam(":sabor", $sabor, PDO::PARAM_INT);

            $stmt->execute();
        };

        // criar o pedido da pizza

        $stmt = $conn->prepare("INSERT INTO pedidos (id_pizza, id_status) VALUES (:pizza,:status)");

        // status sempre inicia com 1 que é de produção

        $statusId = 1;

        // filtrar inputs

        $stmt->bindParam(":pizza", $pizzaId);
        $stmt->bindParam(":status", $statusId);

        $stmt->execute();


        $_SESSION["msg"] = "Pedido realizado com sucesso!";
        $_SESSION["status"] = "success";
    }

    header("Location: ..");
}
