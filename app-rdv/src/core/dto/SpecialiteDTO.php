<?php

namespace rdv\core\dto;

use rdv\core\dto\DTO;

class SpecialiteDTO extends DTO
{
    protected string $id;
    protected string $label;
    protected string $description;

    public function getLabel():string{
        return $this->label;
    }
    public function __construct(string $ID, string $label, string $description)
    {
        $this->id = $ID;
        $this->label = $label;
        $this->description = $description;
    }
}
