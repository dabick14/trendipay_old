<?php

class StaticValues{

    public static $LEVEL_ONE = 1;
    public static $LEVEL_TWO = 2;
    public static $LEVEL_THREE = 3;
    public static $LEVEL_FOUR = 4;


    public static $LEVEL_CONFIRM_AMOUNT = 10;
    public static $LEVEL_PAYMENT_OPTIONS = 11;
    public static $LEVEL_SUCCESSFUL_PAYMENT = 12;


    public static $AIRTIME = array(
        2 => "Select a network provider\n1.MTN\n2.Vodafone\n3.AirtelTigo\n4.Glo",
        3 => "Enter Recipient's Number"
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

    public static $PAYMENT_OPTIONS = "Select a payment option\n1.MTN\n2.Vodafone\n3.AirtelTigo\n4.G-Money";


    /*public function __construct($CONTACT) {
        $this->CONTACT = $CONTACT;
    }*/


}