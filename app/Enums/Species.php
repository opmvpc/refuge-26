<?php

namespace App\Enums;

/**
 * Les espèces accueillies par les refuges. Les valeurs sont en français :
 * ce sont des données, enregistrées en base et lues dans l'adresse (?espece=chat).
 */
enum Species: string
{
    case Dog = 'chien';
    case Cat = 'chat';
    case Rabbit = 'lapin';

    /**
     * Le nom de l'espèce, pour l'affichage : « Chien », « Chat », « Lapin ».
     */
    public function label(): string
    {
        return match ($this) {
            self::Dog => 'Chien',
            self::Cat => 'Chat',
            self::Rabbit => 'Lapin',
        };
    }

    /**
     * Le chemin du pictogramme de l'espèce, à passer à asset().
     */
    public function image(): string
    {
        return 'images/species/'.$this->value.'.svg';
    }
}
