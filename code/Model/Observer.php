<?php
/**
 * Created by PhpStorm.
 * User: W10
 * Date: 18-02-2018
 * Time: 20:14
 */

class Digidennis_WorkSlip_Model_Observer
{
    public function salesOrderCreateProcessData(Varien_Event_Observer $observer)
    {
        $worksliped = Mage::getSingleton('core/session')->getWorkslipedOrder();
        $sessionQuote = $observer->getSessionQuote();
        if( $worksliped ) {
            $workslip = Mage::getModel('digidennis_workslip/workslip')->load($worksliped);
            $customer = Mage::getModel('customer/customer');
            $customer->setStore($sessionQuote->getStore())->loadByEmail($workslip->getEmail());
            if( !$customer->getEntityId() ){
                $names = explode(' ', $workslip->getName());
                $firstname = $names[0]; unset($names[0]); $lastname = implode(' ', $names);
                $customer->setEmail($workslip->getEmail())
                    ->setTelephone($workslip->getPhone())
                    ->setFirstname($firstname)
                    ->setLastname($lastname);
                try{
                    $customer->save();
                }
                catch (Exception $e) {
                    Zend_Debug::dump($e->getMessage());
                }
            }
            $sessionQuote->setCustomer($customer);
            $sessionQuote->setCustomerId($customer->getEntityId());
            Mage::getSingleton('core/session')->unsWorkslipedOrder();
        }
    }
}