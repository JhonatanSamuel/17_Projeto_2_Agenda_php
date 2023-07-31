<?php

session_start();

include_once("connection.php");
include_once("url.php");

$data = $_POST;

//MODIFICAÇÃO DE BANCO
if (!empty($data)) {

    

    //CRIAR CONTATO
    if($data["type"] === "create"){

        $name = $data["name"];
        $phone = $data["phone"];
        $observationS = $data["observationS"];

        $query = "INSERT INTO contacts (name, phone, observationS) VALUES (:name, :phone, :observationS)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":observationS", $observationS);

        try {
        
          $stmt->execute();
          $_SESSION["msg"] = "Contato criado com sucesso!"; 
    
        } catch(PDOException $e) {
            //erro conexão
            $error = $e->getMessage();
            echo "Erro: $error";
        }

    } else if($data["type"] === "edit") {

        $name = $data["name"];
        $phone = $data["phone"];
        $observationS = $data["observationS"];
        $id = $data["id"];

        $query = "UPDATE contacts 
                 SET name = :name, phone = :phone, observationS = :observationS  
                 WHERE id = :id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":observationS", $observationS);
        $stmt->bindParam(":id", $id);

        try {
        
            $stmt->execute();
            $_SESSION["msg"] = "Contato atualizado com sucesso!"; 
      
          } catch(PDOException $e) {
              //erro conexão
              $error = $e->getMessage();
              echo "Erro: $error";
          }



    } else if($data["type"] === "delete" ) {

        $id = $data["id"];

        $query = "DELETE FROM contacts WHERE id = :id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":id", $id);

        

        try {
        
            $stmt->execute();
            $_SESSION["msg"] = "Contato removido com sucesso!"; 
      
          } catch(PDOException $e) {
              //erro conexão
              $error = $e->getMessage();
              echo "Erro: $error";
          }
    }

        // REDIRECT HOME
        header("Location:" . $BASE_URL . "../index.php");

    // SELEÇÃO DE DADOS
    } else {
        $id;

        if (!empty($_GET)) {
            $id = $_GET["id"];
        }

        // Retorna o dado de 1 contato
        if (!empty($id)) {

            $query = "SELECT * FROM contacts WHERE id = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $contact = $stmt->fetch();
        } else {

            // Retorna todos os contatos
            $contacts = [];

            $query = "SELECT * FROM contacts";

            $stmt = $conn->prepare($query);

            $stmt->execute();

            $contacts = $stmt->fetchAll();
        }
    }

    //FECHAR CONEXÃO
    $conn = null;