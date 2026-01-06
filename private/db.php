<?php
// Databaseforbindelse med PDO
// Bruger try-catch til at håndtere fejl sikkert

try{
  // Database-brugernavn
  $dbUserName = 'root';

  // Database-adgangskode
  // (bruges lokalt – bør ikke ligge i klartekst i produktion)
  $dbPassword = 'password'; // root | admin

  // DSN (Data Source Name)
    // mysql = database-type
    // host = database-server
    // dbname = database-navn
    // charset=utf8mb4 understøtter alle tegn + emojis
  $dbConnection = 'mysql:host=mariadb; dbname=company; charset=utf8mb4'; 
  
  // PDO-indstillinger
  $options = [

    // Gør PDO-fejl til exceptions (bruges sammen med try-catch)
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // try-catch

    // Standard fetch mode: associative arrays
    // Resultat: ['nickname' => 'value']
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // ['nickname']
    
    // Andre fetch-modes (eksempler):
        // PDO::FETCH_OBJ -> $row->nickname
        // PDO::FETCH_NUM -> [0 => 'value']
  ];

  // Opretter PDO-instansen (databaseforbindelsen)
  $_db = new PDO(  $dbConnection, 
                  $dbUserName, 
                  $dbPassword , 
                  $options );
  
}catch(PDOException $ex){
  // Viser fejlbesked (kun til udvikling)
  echo $ex;  

  // Stopper eksekveringen hvis forbindelsen fejler
  exit(); // eller die
}
