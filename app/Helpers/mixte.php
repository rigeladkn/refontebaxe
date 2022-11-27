<?php

if (!function_exists('convertir_un_nombre_en_pourcentage'))
{
    function convertir_un_nombre_en_pourcentage($valeur_partiel, $valeur_totale)
    {
        return (100 * $valeur_partiel) / $valeur_totale;
    }
}

if (!function_exists('convertir_un_pourcentage_en_nombre'))
{
    function convertir_un_pourcentage_en_nombre($pourcentage, $valeur)
    {
        return ( ($pourcentage * $valeur) / 100 );
    }
}

if (!function_exists('get_type_carte_credit'))
{
    function get_type_carte_credit($numerco_carte, $inclure_sous_type = false)
    {
        $visa_regex = "/^4[0-9]{0,}$/";
        $vpreca_regex = "/^428485[0-9]{0,}$/";
        $postepay_regex = "/^(402360|402361|403035|417631|529948){0,}$/";
        $cartasi_regex = "/^(432917|432930|453998)[0-9]{0,}$/";
        $entropay_regex = "/^(406742|410162|431380|459061|533844|522093)[0-9]{0,}$/";
        $o2money_regex = "/^(422793|475743)[0-9]{0,}$/";

        // MasterCard
        $mastercard_regex = "/^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$/";
        $maestro_regex = "/^(5[06789]|6)[0-9]{0,}$/";
        $kukuruza_regex = "/^525477[0-9]{0,}$/";
        $yunacard_regex = "/^541275[0-9]{0,}$/";

        // American Express
        $amex_regex = "/^3[47][0-9]{0,}$/";

        // Diners Club
        $diners_regex = "/^3(?:0[0-59]{1}|[689])[0-9]{0,}$/";

        //Discover
        $discover_regex = "/^(6011|65|64[4-9]|62212[6-9]|6221[3-9]|622[2-8]|6229[01]|62292[0-5])[0-9]{0,}$/";

        //JCB
        $jcb_regex = "/^(?:2131|1800|35)[0-9]{0,}$/";


        if ($inclure_sous_type) {
            if (preg_match($vpreca_regex, $numerco_carte)) {
                return "v-preca";
            }
            if (preg_match($postepay_regex, $numerco_carte)) {
                return "postepay";
            }
            if (preg_match($cartasi_regex, $numerco_carte)) {
                return "cartasi";
            }
            if (preg_match($entropay_regex, $numerco_carte)) {
                return "entropay";
            }
            if (preg_match($o2money_regex, $numerco_carte)) {
                return "o2money";
            }
            if (preg_match($kukuruza_regex, $numerco_carte)) {
                return "kukuruza";
            }
            if (preg_match($yunacard_regex, $numerco_carte)) {
                return "yunacard";
            }
        }

        if (preg_match($jcb_regex, $numerco_carte)) {
            return "jcb";
        }

        if (preg_match($amex_regex, $numerco_carte)) {
            return "amex";
        }

        if (preg_match($diners_regex, $numerco_carte)) {
            return "diners_club";
        }

        if (preg_match($visa_regex, $numerco_carte)) {
            return "visa";
        }

        if (preg_match($mastercard_regex, $numerco_carte)) {
            return "mastercard";
        }

        if (preg_match($discover_regex, $numerco_carte)) {
            return "discover";
        }

        if (preg_match($maestro_regex, $numerco_carte)) {
            if ($numerco_carte[0] == '5') {
                return "mastercard";
            }
            return "maestro";
        }

        return "inconnu";
    }
}

/**
* Help for Better Comments
* * Important
* ! Alert ou deprecier
* ? Question
* TODO A faire
* @param parametre
* //// Barré
*/

/**
 * Help extension TODO List
TODO– quelque chose à faire.
FIXME– doit être corrigé.
HACK– une solution de contournement.
BUG– un bogue connu qui devrait être corrigé.
UNDONE– une inversion ou "roll back" du code précédent.
*/
