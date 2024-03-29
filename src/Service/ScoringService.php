<?php
/*
 * Сервис для работы со скорингом
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Service;

/**
 * Класс скоринг-сервиса
 */
class ScoringService
{
    /**
     * Рассчитывает балл для оператора мобильного телефона
     * 
     * @param String $phone Номер телефона в формате 7xxxxxxxxxx
     */
    public function getProdiverScore(String $phone): int
    {
        $providerCode = (int)substr($phone, 1, 3);

        //Если МТС
        if(($providerCode >= 910 && $providerCode <= 919) || 
            ($providerCode >= 980 && $providerCode <= 989)){
                return 3;
        }    

        //Если Билайн
        if(($providerCode >= 903 && $providerCode <= 906) || 
            ($providerCode >= 960 && $providerCode <= 968)){
                return 5;
        }

        //Если Мегафон
        if($providerCode >= 920 && $providerCode <= 938){
            return 10;
        }

        //Если иной оператор
        return 1;
    }

    /**
     * Рассчитывает балл за уровень образования
     * 
     * @param String $education Название уровня образования
     */
    public function getEducationScore(String $education): int
    {
        //Если специальное
        if (strcasecmp($education, 'Специальное образование') == 0){
            return 10;
        }

        //Если высшее
        if (strcasecmp($education, 'Высшее образование') == 0){
            return 15;
        }
        
        //Если среднее
        if (strcasecmp($education, 'Среднее образование') == 0){
            return 5;
        }
    }

    /**
     * Рассчитывает балл за согласие на обработку данных
     * 
     * @param bool $agree Согласие (true или false)
     */
    public function getAgreeScore(bool $agree): int
    {
        //Если галочка установлена 
        if ($agree){
            return 4;
        }

        //Если не установлена
        return 0;
    }

    /**
     * Рассчитывает балл за домен электронной почты
     * 
     * @param String $email Адрес электронной почты
     */
    public function getDomainScore(String $email): int 
    {
        $mailDomain = substr($email, 0, strripos($email, '.')); 
        $mailDomain = substr($mailDomain, strripos($mailDomain, '@')+1); 

        //Если mail
        if (strcasecmp($mailDomain, 'mail') == 0){
            return 6;
        }

        //Если yandex
        if (strcasecmp($mailDomain, 'yandex') == 0){
            return 8;
        }

        //Если gmail
        if (strcasecmp($mailDomain, 'gmail') == 0){
            return 10;
        }

        //иной
        return 3;
    }

    /**
     * Расчет скоринга для клиента во время регистрации
     * 
     * @param String $phone Номер мобильного телефона
     * @param String $email Адрес электронной почты
     * @param String $education Название уровня образования
     * @param bool $agree Согласие (true или false)
     */
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

    /**
     * Расчет скоринга для консольной команды
     * 
     * @param String $phone Номер мобильного телефона
     * @param String $email Адрес электронной почты
     * @param String $education Название уровня образования
     * @param bool $agree Согласие (true или false)
     */
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