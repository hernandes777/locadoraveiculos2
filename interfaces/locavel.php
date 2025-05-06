<?php
namespace Interfaces;

// Interface que define os métodos necessarios para o veículo ser locàvel 

interface Locavel {
    public function alugar() : string;
    public function devolver() : string;
    public function isDisponivel(): bool;
}