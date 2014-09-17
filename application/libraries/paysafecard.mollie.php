<?php
/**
 * PaySafeCard Payment class written by Mollie
 *
 * @author Mollie BV <info@mollie.nl>
 * @copyright Copyright (c) November, 2011
 * @version 1.00
 */
class PaySafeCard_Payment
{
	const     MIN_TRANS_AMOUNT = 100;

	protected $partner_id      = null;
	protected $profile_key     = null;
	protected $customer_ref    = null;
	protected $amount          = 0;
	protected $return_url      = null;
	protected $report_url      = null;

	protected $payment_url     = null;

	protected $transaction_id  = null;
	protected $paid_status     = false;

	protected $error_message   = '';
	protected $error_code      = 0;

	protected $api_host        = 'ssl://secure.mollie.nl';
	protected $api_port        = 443;

	public function __construct ($partner_id, $api_host = 'ssl://secure.mollie.nl', $api_port = 443)
	{
		$this->partner_id = $partner_id;
		$this->api_host   = $api_host;
		$this->api_port   = $api_port;
	}

	// Prepare a payment with the bank and make the payment url available
	public function createPayment ($amount, $profile_key, $customer_ref, $return_url, $report_url)
	{
		if (!$this->setAmount($amount) or
			!$this->setProfileKey($profile_key) or
			!$this->setCustomerRef($customer_ref) or
			!$this->setReturnUrl($return_url) or
			!$this->setReportUrl($report_url))
		{
			$this->error_message = "De opgegeven betalings gegevens zijn onjuist of incompleet."; //The chosen payment info are incorrect or incomplete
			return false;
		}

		$query_variables = array(
			'partnerid'    => $this->getPartnerId(),
			'amount'       => $this->getAmount(),
			'profile_key'  => $this->getProfileKey(),
			'customer_ref' => $this->getCustomerRef(),
			'reporturl'    => $this->getReportURL(),
			'returnurl'    => $this->getReturnURL(),
		);
                
                //echo "</br>"; print_r($query_variables);
                
		$create_xml = $this->_sendRequest(
			$this->api_host,
			$this->api_port,
			'/xml/paysafecard/prepare/',
			http_build_query($query_variables, '', '&')
		);

		if (empty($create_xml)) {
			return false;
		}

		$create_object = $this->_XMLtoObject($create_xml);
		if (!$create_object or $this->_XMLisError($create_object)) {
			return false;
		}

		$this->transaction_id = (string) $create_object->order->transaction_id;
		$this->payment_url    = (string) $create_object->order->url;

		return true;
	}

	// Check if it is really paid
	public function checkPayment ($transaction_id)
	{
		if (!$this->setTransactionId($transaction_id)) {
			$this->error_message = "Er is een onjuist transactie ID opgegeven"; //Wrong transatcion ID
			return false;
		}
		
		$query_variables = array (
			'partnerid'      => $this->partner_id,
			'transaction_id' => $this->getTransactionId(),
		);

		$check_xml = $this->_sendRequest(
			$this->api_host,
			$this->api_port,
			'/xml/paysafecard/check-status/',
			http_build_query($query_variables, '', '&')
			);

		if (empty($check_xml))
			return false;

		$check_object = $this->_XMLtoObject($check_xml);

		if (!$check_object or $this->_XMLisError($check_object)) {
			return false;
		}

		$this->paid_status   = (bool) ($check_object->order->paid == 'true');
		$this->status        = (string) $check_object->order->status;
		$this->amount        = (int) $check_object->order->amount;
		return true;
	}

/*
	PROTECTED FUNCTIONS
*/

	protected function _sendRequest ($host, $port, $path, $data)
	{
		if (function_exists('curl_init')) {
			return $this->_sendRequestCurl($host, $port, $path, $data);
		}
		else {
			return $this->_sendRequestFsock($host, $port, $path, $data);
		}
	}

	protected function _sendRequestFsock ($host, $port, $path, $data)
	{
		$hostname = str_replace('ssl://', '', $host);
		$fp = @fsockopen($host, $port, $errno, $errstr);
		$buf = '';

		if (!$fp)
		{
			$this->error_message = 'Kon geen verbinding maken met server: ' . $errstr; //Could not establish communication with the server
			$this->error_code		= 0;

			return false;
		}

		@fputs($fp, "POST $path HTTP/1.0\n");
		@fputs($fp, "Host: $hostname\n");
		@fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
		@fputs($fp, "Content-length: " . strlen($data) . "\n");
		@fputs($fp, "Connection: close\n\n");
		@fputs($fp, $data);

		while (!feof($fp)) {
			$buf .= fgets($fp, 128);
		}

		fclose($fp);

		if (empty($buf))
		{
			$this->error_message = 'Zero-sized reply';
			return false;
		}
		else {
			list($headers, $body) = preg_split("/(\r?\n){2}/", $buf, 2);
		}

		return $body;
	}
	
	protected function _sendRequestCurl ($host, $port, $path, $data)
	{
		$host = str_replace('ssl://', 'https://', $host);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $host . $path . '?' . $data);
		curl_setopt($ch, CURLOPT_PORT, $port);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HEADER, false);
		
		$body = curl_exec($ch);

		curl_close($ch);
		
		return $body;
	}

	protected function _XMLtoObject ($xml)
	{
		try
		{
			$xml_object = new SimpleXMLElement($xml);
			if ($xml_object == false)
			{
				$this->error_message = "Kon XML resultaat niet verwerken";
				return false;
			}
		}
		catch (Exception $e) {
			return false;
		}

		return $xml_object;
	}

	protected function _XMLisError($xml)
	{
		if (isset($xml->item))
		{
			$attributes = $xml->item->attributes();
			if ($attributes['type'] == 'error')
			{
				$this->error_message = (string) $xml->item->message;
				$this->error_code    = (string) $xml->item->errorcode;

				return true;
			}
		}

		return false;
	}


	/* Getters en setters */
	public function setProfileKey($profile_key)
	{		
		if (is_null($profile_key))
			return false;
			
		return ($this->profile_key = $profile_key);
	}
	
	public function getProfileKey()
	{
		return $this->profile_key;
	}
	
	public function setCustomerRef($customer_ref)
	{
		if (is_null($customer_ref))
			return false;

		return ($this->customer_ref = $customer_ref);
	}

	public function getCustomerRef()
	{
		return $this->customer_ref;
	}

	public function setPartnerId ($partner_id)
	{
		if (!is_numeric($partner_id)) {
			return false;
		}

		return ($this->partner_id = $partner_id);
	}

	public function getPartnerId ()
	{
		return $this->partner_id;
	}

	public function setAmount ($amount)
	{
		if (!preg_match('~^[0-9]+$~', $amount)) {
			return false;
		}

		if (self::MIN_TRANS_AMOUNT > $amount) {
			return false;
		}

		return ($this->amount = $amount);
	}

	public function getAmount ()
	{
		return $this->amount;
	}

	public function setReturnURL ($return_url)
	{
		if (!preg_match('|(\w+)://([^/:]+)(:\d+)?(.*)|', $return_url))
			return false;

		return ($this->return_url = $return_url);
	}

	public function getReturnURL ()
	{
		return $this->return_url;
	}

	public function setReportURL ($report_url)
	{
		if (!preg_match('|(\w+)://([^/:]+)(:\d+)?(.*)|', $report_url)) {
			return false;
		}

		return ($this->report_url = $report_url);
	}

	public function getReportURL ()
	{
		return $this->report_url;
	}

	public function setTransactionId ($transaction_id)
	{
		if (empty($transaction_id))
			return false;

		return ($this->transaction_id = $transaction_id);
	}

	public function getTransactionId ()
	{
		return $this->transaction_id;
	}

	public function getPaymentURL ()
	{
		return $this->payment_url;
	}

	public function getPaidStatus ()
	{
		return $this->paid_status;
	}
	
	public function getBankStatus()
	{
		return $this->status;
	}

	public function getErrorMessage ()
	{
		return $this->error_message;
	}

	public function getErrorCode ()
	{
		return $this->error_code;
	}
	
}
