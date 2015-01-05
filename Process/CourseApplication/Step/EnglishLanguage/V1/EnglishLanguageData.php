<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\EnglishLanguage\V1;

class EnglishLanguageData
{
    private $englishFirstLanguage;
    private $englishTest;
    private $trfNumber;
    private $caeCpaIdNumber;
    private $caeCpaSecret;
    private $noTestAgreement;
    private $nationality;
    private $qualifications;

    /**
     * @param mixed $englishFirstLanguage
     * @return EnglishLanguageData
     */
    public function setEnglishFirstLanguage($englishFirstLanguage)
    {
        $this->englishFirstLanguage = $englishFirstLanguage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnglishFirstLanguage()
    {
        return $this->englishFirstLanguage;
    }

    /**
     * @param mixed $englishTest
     * @return EnglishLanguageData
     */
    public function setEnglishTest($englishTest)
    {
        $this->englishTest = $englishTest;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnglishTest()
    {
        return $this->englishTest;
    }

    /**
     * @param mixed $tflNumber
     * @return EnglishLanguageData
     */
    public function setTrfNumber($tflNumber)
    {
        $this->trfNumber = $tflNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrfNumber()
    {
        return $this->trfNumber;
    }

    /**
     * @param mixed $caeCpaNumber
     * @return EnglishLanguageData
     */
    public function setCaeCpaIdNumber($caeCpaNumber)
    {
        $this->caeCpaIdNumber = $caeCpaNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaeCpaIdNumber()
    {
        return $this->caeCpaIdNumber;
    }

    /**
     * @param mixed $caeCpaSecret
     * @return EnglishLanguageData
     */
    public function setCaeCpaSecret($caeCpaSecret)
    {
        $this->caeCpaSecret = $caeCpaSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaeCpaSecret()
    {
        return $this->caeCpaSecret;
    }

    /**
     * @param mixed $noTestAgreement
     * @return EnglishLanguageData
     */
    public function setNoTestAgreement($noTestAgreement)
    {
        $this->noTestAgreement = $noTestAgreement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoTestAgreement()
    {
        return $this->noTestAgreement;
    }

    /**
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     */
    public function setNationality($nationality = '')
    {
        $this->nationality = $nationality;
    }

    /**
     * @return string
     */
    public function getQualifications()
    {
        return $this->qualifications;
    }

    /**
     * @param string $qualifications
     */
    public function setQualifications($qualifications = '')
    {
        $this->qualifications = $qualifications;
    }
}
