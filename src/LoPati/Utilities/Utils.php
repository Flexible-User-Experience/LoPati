<?php
namespace LoPati\Utilities;

class Utils 
{
    static public function getStringMonth($monthValue)
    {
        if ($monthValue == 1) {
            return 'enero';
        } else if ($monthValue == 2) {
            return 'febrero';
        } else if ($monthValue == 3) {
            return 'marzo';
        } else if ($monthValue == 4) {
            return 'abril';
        } else if ($monthValue == 5) {
            return 'mayo';
        } else if ($monthValue == 6) {
            return 'junio';
        } else if ($monthValue == 7) {
            return 'julio';
        } else if ($monthValue == 8) {
            return 'agosto';
        } else if ($monthValue == 9) {
            return 'septiembre';
        } else if ($monthValue == 10) {
            return 'octubre';
        } else if ($monthValue == 11) {
            return 'noviembre';
        } else if ($monthValue == 12) {
            return 'diciembre';
        }

        return 'indefinido';
    }

    static public function getSlug($cadena, $separador = '-') 
    {
        // reemplaça vocals amb accents per vocals sense accent
        $cadena = str_replace('À', 'A', $cadena);
        $cadena = str_replace('Á', 'A', $cadena);
        $cadena = str_replace('Ä', 'A', $cadena);
        $cadena = str_replace('Â', 'A', $cadena);
        $cadena = str_replace('È', 'E', $cadena);
        $cadena = str_replace('É', 'E', $cadena);
        $cadena = str_replace('Ë', 'E', $cadena);
        $cadena = str_replace('Ê', 'E', $cadena);
        $cadena = str_replace('Ì', 'I', $cadena);
        $cadena = str_replace('Í', 'I', $cadena);
        $cadena = str_replace('Ï', 'I', $cadena);
        $cadena = str_replace('Î', 'I', $cadena);
        $cadena = str_replace('Ò', 'O', $cadena);
        $cadena = str_replace('Ó', 'O', $cadena);
        $cadena = str_replace('Ö', 'O', $cadena);
        $cadena = str_replace('Ô', 'O', $cadena);
        $cadena = str_replace('Ù', 'U', $cadena);
        $cadena = str_replace('Ú', 'U', $cadena);
        $cadena = str_replace('Ü', 'U', $cadena);
        $cadena = str_replace('Û', 'U', $cadena);
        $cadena = str_replace('à', 'a', $cadena);
        $cadena = str_replace('á', 'a', $cadena);
        $cadena = str_replace('ä', 'a', $cadena);
        $cadena = str_replace('â', 'a', $cadena);
        $cadena = str_replace('è', 'e', $cadena);
        $cadena = str_replace('é', 'e', $cadena);
        $cadena = str_replace('ë', 'e', $cadena);
        $cadena = str_replace('ê', 'e', $cadena);
        $cadena = str_replace('ì', 'i', $cadena);
        $cadena = str_replace('í', 'i', $cadena);
        $cadena = str_replace('ï', 'i', $cadena);
        $cadena = str_replace('î', 'i', $cadena);
        $cadena = str_replace('ò', 'o', $cadena);
        $cadena = str_replace('ó', 'o', $cadena);
        $cadena = str_replace('ö', 'o', $cadena);
        $cadena = str_replace('ô', 'o', $cadena);
        $cadena = str_replace('ù', 'u', $cadena);
        $cadena = str_replace('ú', 'u', $cadena);
        $cadena = str_replace('ü', 'u', $cadena);
        $cadena = str_replace('û', 'u', $cadena);
        // reemplaça altres simbols extranys
        $cadena = str_replace('Ç', 'C', $cadena);
        $cadena = str_replace('ç', 'c', $cadena);
        $cadena = str_replace('Ñ', 'NY', $cadena);
        $cadena = str_replace('ñ', 'ny', $cadena);
        $cadena = str_replace('\'', '-', $cadena);
        // Codigo copiado de http://cubiq.org/the-perfect-php-clean-url-generator
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);

        return $slug;
    }

    static public function getVideo($video) {
        $tipus_video = parse_url($video);
        if (($tipus_video["host"] == "vimeo.com") || ($tipus_video["host"] == "www.vimeo.com")) {
            $tipus_video["path"];
            $video2 = '<br><iframe class="videovimeo" src="http://player.vimeo.com/video' . $tipus_video["path"] . '"?title=0&amp;byline=0&amp;portrait=0" frameborder="0"></iframe><br>';

            return $video2;
        } elseif (($tipus_video["host"] == "youtube.com") || ($tipus_video["host"] == "www.youtube.com") || ($tipus_video["host"] == "youtu.be" )) {
            if ($tipus_video["host"] == "youtu.be" ){
                $out = $tipus_video["path"];
                $video2 = '<br><iframe class="videoyoutube" src="http://www.youtube.com/embed/'	. $out . '" frameborder="0" allowfullscreen></iframe><br>';

                return $video2;
            } else {
                parse_str($tipus_video["query"], $output);
                $out = $output['v'];
                $video2 = '<br><iframe class="videoyoutube" src="http://www.youtube.com/embed/'	. $out . '" frameborder="0" allowfullscreen></iframe><br>';

                return $video2;
            }
        }

        return null;
    }
}
