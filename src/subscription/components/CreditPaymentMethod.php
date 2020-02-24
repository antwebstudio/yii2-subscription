<?php
namespace ant\subscription\components;

class CreditPaymentMethod extends \ant\payment\components\CreditPaymentMethod {
	
	public function getPaymentRecordData() {
        $data = isset($this->_response) ? $this->_response->getData() : [];
        
        $data['data'] = $data;
		$data['transaction_id'] = uniqid();
		
		return $data;
    }
}