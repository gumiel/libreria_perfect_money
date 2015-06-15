<?php 
/**
* 
*/
class LibPerfectMoney 
{
	private $idDelPago; // Este es el dato del PAYMENT_ID
	private $cuentaRecibe; // Este es el dato de PAYEE_ACCOUNT, Es la cuenta de la IPN
	private $CuentaEnvia; // este es el dato del PAYER_ACCOUNT, Es 
	private $montoEnviado; // Este es el monto que se pago a la cuenta de la IPN  valor PAYMENT_AMOUNT
	private $unidades; // Este es tipo de la unidad que puede ser USD o EUR
	private $batchNum; // falta
	private $passAlternativo; // este es el password alternativo que se configura en la cuenta de IPN
	private $timestampgmt; // falta
	public $numeroLog;
	
	function __construct($config)
	{
		$this->idDelPago          = (isset($_POST['PAYMENT_ID']))? $_POST['PAYMENT_ID']:'';
		$this->cuentaRecibe       = (isset($_POST['PAYEE_ACCOUNT']))? $_POST['PAYEE_ACCOUNT']:'';
		$this->cuentaRecibeConfig = $config['cuentaIPN'];
		$this->CuentaEnvia        = (isset($_POST['PAYER_ACCOUNT']))? $_POST['PAYER_ACCOUNT']:'';
		$this->montoEnviado       = (isset($_POST['PAYMENT_AMOUNT']))? $_POST['PAYMENT_AMOUNT']:'';
		$this->unidades           = (isset($_POST['PAYMENT_UNITS']))? $_POST['PAYMENT_UNITS']:'';
		$this->batchNum           = (isset($_POST['PAYMENT_BATCH_NUM']))? $_POST['PAYMENT_BATCH_NUM']:'';
		$this->passAlternativo    = $config['passAlternativo'];
		$this->timestampgmt       = (isset($_POST['TIMESTAMPGMT']))? $_POST['TIMESTAMPGMT']:'';
		$this->numeroLog          = '#'.date("ym-dHis");

	}

	public function paso1($id='')
	{		
		$this->log('------------INICIO '.$this->numeroLog.'----------------------------------------------------');
		if( $this->verificarDatosPOST() )
		{
			if( $this->verificarPAYEE_ACCOUNT() )
			{
				if( $this->comparacionV2_HASH() ){

				} else
				{
					return array('result'=>'error','msg'=>'El valor V2_HASH no coincide con los datos');
				}
			} else
			{
				return array('result'=>'error','msg'=>'La cuenta donde recibe no coindice con la configuracion');
			}
		} else
		{
			return array('result'=>'error','msg'=>'Existe un error en los valores POST');
		}
		$this->log('------------FIN---------------------------------------------------');
	}

	public function comparacionV2_HASH()
	{
		$string = $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.$_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.$_POST['PAYMENT_BATCH_NUM'].':'.$_POST['PAYER_ACCOUNT'].':'.$this->passAlternativo.':'.$_POST['TIMESTAMPGMT'];
        $hash = strtoupper(md5($string));

        if( $hash!=$_POST['V2_HASH'] )
        {
        	$this->log('La comparacion de los valores POST no coincide con el valor V2_HASH');
        	return FALSE;
        }else{
        	return TRUE;
        }
	}

	public function verificarDatosPOST()
	{
		if (!isset($_POST['PAYMENT_ID'])) {
			$this->log('El valor del "PAYMENT_ID" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else if ( !isset($_POST['PAYEE_ACCOUNT']) ) {
			$this->log('El valor del "PAYEE_ACCOUNT" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else if ( !isset($_POST['PAYER_ACCOUNT']) ) {
			$this->log('El valor del "PAYEE_ACCOUNT" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else if ( !isset($_POST['PAYMENT_AMOUNT']) ) {
			$this->log('El valor del "PAYMENT_AMOUNT" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else if ( !isset($_POST['PAYMENT_UNITS']) ) {
			$this->log('El valor del "PAYMENT_UNITS" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else if ( !isset($_POST['PAYMENT_BATCH_NUM']) ) {
			$this->log('El valor del "PAYMENT_BATCH_NUM" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else if ( !isset($_POST['TIMESTAMPGMT']) ) {
			$this->log('El valor del "TIMESTAMPGMT" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else if ( !isset($_POST['V2_HASH']) ) {
			$this->log('El valor del "V2_HASH" no es EXISTE en el POST que nos enviaron');
			return FALSE;
		} else{
			return TRUE;
		}
		
	}

	public function verificarPAYEE_ACCOUNT()
	{
		if ($this->cuentaRecibe != $this->cuentaRecibeConfig)
		{
			$this->log('El valor del "PAYEE_ACCOUNT" no es correcto. La cuenta de la IPN no coincide con la enviada');
			return FALSE;
		} 		
		else
		{
			return TRUE;
		}
	}

	private function log($contenido)
	{
		if (!$gestor = fopen("perfectmoney.log", 'a'))
		    return;		

		$linia = date("Y-m-d :H:i:s")." ".$contenido."\n";
		  
		if (fwrite($gestor, $linia) === FALSE)
		    return;		

		fclose($gestor);
	}
}

 ?>

 <style type="text/css">
 .hola:nthchidl()</style>