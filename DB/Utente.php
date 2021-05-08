<?php

class Utente
{
    private $id;
    private $nome;
    private $cognome;
    private $telefono;
    private $email;
    private $password;

    public function __construct($id, $nome, $cognome, $telefono, $email, $password)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    public function getCognome()
    {
        return $this->cognome;
    }
    public function setCognome($cognome): void
    {
        $this->cognome = $cognome;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}
