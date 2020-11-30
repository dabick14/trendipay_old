<?php

class StaticValues {

    public static $LEVEL_ONE = 1;
    public static $LEVEL_TWO = 2;
    public static $LEVEL_THREE = 3;
    public static $LEVEL_FOUR = 4;
    public static $LEVEL_FIVE = 5;
    public static $LEVEL_SIX= 6;
    public static $LEVEL_SEVEN = 7;


    public static $LEVEL_CONFIRM_AMOUNT = 10;
    public static $LEVEL_PROCESS_CONFIRMATION = 11;
    public static $LEVEL_PAYMENT_OPTIONS = 12;
    public static $LEVEL_SUCCESSFUL_PAYMENT = 13;
    public static $LEVEL_EDIT = 14;


    public static $AIRTIME = array(
        2 => "Select a network provider\n1.MTN\n2.Vodafone\n3.AirtelTigo\n4.Glo",
        3 => "Enter Recipient's Number",
        4 => "Enter Amount"
    );

    public static $BUNDLE = array(
        2 => "Select a network provider\n1.MTN\n2.Vodafone\n3.AirtelTigo\n4.Glo\n 5.LTE",
        3 => "Choose Data Bundle",
        4 => "Enter Recipient's Number"
    );

    public static $BILL_PAYMENT = array(
        2 => array("Bill Payments\n1.SunPower\n2.ECG\n3.DSTV/GOTv\n4.Water"),
        3 => array("Enter account number"),
        4 => array("")
    );

    public static $MERCHANTS = array(
        2 => array("Merchants\n1.Airlines\n2.Other Merchants\n"),
        3 => array("Enter account number"),
        4 => array("")
    );

    public static $CONTACT = array(
        2 => array("Contact Us\nwebsite:www.broadspectrum.com.gh\nemail:contact@broadspectrumltd.com\n"),
    );

    public static $PAYMENT_OPTIONS = array( 
        1 => "Select a payment option\n1.MTN\n2.Vodafone\n3.AirtelTigo\n4.G-Money",
        2 => "What would you like to edit?\n1.Mobile Network\n2.Phone Number\n3.Amount"
    );
    
    
    public static $EDIT_OPTIONS = array( 
        1 => "Edit network provider\n1.MTN\n2.Vodafone\n3.AirtelTigo\n4.Glo",
        2 => "Edit Recipient Amount",
        3 => "Edit Amount"
    );

    



    /**
     * @return string[][]
     */
    public static function getCONTACT(): array
    {
        return self::$CONTACT;
    }

    /**
     * @return int
     */
    public static function getLEVELONE(): int
    {
        return self::$LEVEL_ONE;
    }

    /**
     * @return int
     */
    public static function getLEVELTWO(): int
    {
        return self::$LEVEL_TWO;
    }

    /**
     * @return int
     */
    public static function getLEVELTHREE(): int
    {
        return self::$LEVEL_THREE;
    }

    /**
     * @return int
     */
    public static function getLEVELFOUR(): int
    {
        return self::$LEVEL_FOUR;
    }

    /**
     * @return int
     */
    public static function getLEVELCONFIRMAMOUNT(): int
    {
        return self::$LEVEL_CONFIRM_AMOUNT;
    }

    /**
     * @return int
     */
    public static function getLEVELPAYMENTOPTIONS(): int
    {
        return self::$LEVEL_PAYMENT_OPTIONS;
    }

    /**
     * @return int
     */
    public static function getLevelSuccessfulPayment(): int
    {
        return self::$LEVEL_SUCCESSFUL_PAYMENT;
    }

    /**
     * @return string[]
     */
    public static function getAirtime(): array
    {
        return self::$AIRTIME;
    }

    /**
     * @return string[]
     */
    public static function getBundle(): array
    {
        return self::$BUNDLE;
    }

    /**
     * @return string[][]
     */
    public static function getBillPayment(): array
    {
        return self::$BILL_PAYMENT;
    }

    /**
     * @return string[][]
     */
    public static function getMERCHANTS(): array
    {
        return self::$MERCHANTS;
    }

    /**
     * @return string
     */
    public static function getPAYMENTOPTIONS(): string
    {
        return self::$PAYMENT_OPTIONS;
    }





}