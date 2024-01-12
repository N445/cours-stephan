<?php

namespace App\Service\Helper;

class ColorHelper
{
    public static function hexatoRgba($hex, $alpha = 1.0): string
    {
        // Supprimer le caractère "#" s'il est présent
        $hex = str_replace("#", "", $hex);

        // Vérifier si la valeur hexadécimale est une courte ou longue notation
        if (strlen($hex) == 3) {
            // Notation courte, convertir en notation longue
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        // Extraire les composantes de couleur
        $red   = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue  = hexdec(substr($hex, 4, 2));

        // Assurer que l'alpha est compris entre 0.0 et 1.0
        $alpha = min(1.0, max(0.0, $alpha));

        // Retourner la valeur RGBA
        return "rgba($red, $green, $blue, $alpha)";
    }
}