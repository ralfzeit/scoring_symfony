<?php

namespace App\Service;

class ScoringService
{
    public function getProdiverScore(String $phone): int
    {
        $providerCode = (int)substr($phone, 1, 3);

        //Если МТС
        if (($providerCode >= 910 && $providerCode <= 919) || 
            ($providerCode >= 980 && $providerCode <= 989))
                return 3;
        //Если Билайн
        else if (($providerCode >= 903 && $providerCode <= 906) || 
                    ($providerCode >= 960 && $providerCode <= 968))
                        return 5;
        //Если Мегафон
        else if ($providerCode >= 920 && $providerCode <= 938)
                    return 10;
        //Если иной оператор
        else return 1;
    }

    public function getEducationScore(String $education): int
    {
        //Если специальное
        if (strcasecmp($education, 'Специальное образование') == 0)
            return 10;
        //Если высшее
        else if (strcasecmp($education, 'Высшее образование') == 0)
            return 15;
        //Если среднее
        else return 5;
    }

    public function getAgreeScore(bool $agree): int
    {
        //Если галочка установлена 
        if ($agree)
            return 4;
        //Если не установлена
        else 
            return 0;
    }

    public function getDomainScore(String $email): int 
    {
        $mailDomain = substr($email, 0, strripos($email, '.')); 
        $mailDomain = substr($mailDomain, strripos($mailDomain, '@')+1); 

        //Если mail
        if (strcasecmp($mailDomain, 'mail') == 0)
            return 6;
        //Если yandex
        else if (strcasecmp($mailDomain, 'yandex') == 0)
            return 8;
        //Если gmail
        else if (strcasecmp($mailDomain, 'gmail') == 0)
            return 10;
        //иной
        else return 3;
    }

    public function calculateScoreReg(String $phone, String $email, String $education, bool $agree): string
    {
        //Количество баллов за оператора
        $providerScore = $this->getProdiverScore($phone);

        //Количество баллов за образование
        $educationScore = $this->getEducationScore($education);
        
        //Количество баллов за галочку
        $agreeScore = $this->getAgreeScore($agree);
        
        //Количество баллов за домен
        $domainScore = $this->getDomainScore($email);

        return $providerScore + $domainScore + $agreeScore + $educationScore;
    }

    public function calculateScoreForConsole(String $phone, String $email, String $education, bool $agree): array
    {
        $details = array(
            "provider" => $this->getProdiverScore($phone),
            "domain" => $this->getDomainScore($email),
            "education" => $this->getEducationScore($education),
            "agree" => $this->getAgreeScore($agree),
            "scoring" => $this->calculateScoreReg($phone, $email, $education, $agree),
        );

        return $details;
    }
}