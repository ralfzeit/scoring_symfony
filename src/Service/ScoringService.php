<?php

namespace App\Service;

use App\Entity\Client;

class ScoringService
{
    public function calculateScore(String $phone, String $email, String $education, bool $agree): string
    {
        $providerCode = (int)substr($phone, 1, 3);
        $mailDomain = substr($email, 0, strripos($email, '.')); 
        $mailDomain = substr($mailDomain, strripos($mailDomain, '@')+1); 

        //Количество баллов за иного оператора
        $providerScore = 1;
        //Количество баллов за среднее образование
        $educationScore = 5;
        //Количество баллов за отсутствие галочки
        $agreeScore = 0;
        //Количество баллов за иной домен
        $domainScore = 3;

        //Если МТС
        if (($providerCode >= 910 && $providerCode <= 919) || 
            ($providerCode >= 980 && $providerCode <= 989))
                $providerScore = 3;
        //Если Билайн
        else if (($providerCode >= 903 && $providerCode <= 906) || 
                    ($providerCode >= 960 && $providerCode <= 968))
                        $providerScore = 5;
        //Если Мегафон
        else if ($providerCode >= 920 && $providerCode <= 938)
                    $providerScore = 10;

        //Если специальное
        if (strcasecmp($education, 'Специальное образование') == 0)
            $educationScore = 10;
        //Если высшее
        else if (strcasecmp($education, 'Высшее образование') == 0)
            $educationScore = 15;

        //Если mail
        if (strcasecmp($mailDomain, 'mail') == 0)
            $domainScore = 6;
        //Если yandex
        else if (strcasecmp($mailDomain, 'yandex') == 0)
            $domainScore = 8;
        //Если gmail
        else if (strcasecmp($mailDomain, 'gmail') == 0)
            $domainScore = 10;

        //Если галочка установлена 
        if ($agree)
            $agreeScore = 4;

        return $providerScore + $domainScore + $agreeScore + $educationScore;
    }
}