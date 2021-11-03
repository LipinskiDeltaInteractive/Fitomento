<?php
/**
* Author:	Omar Muhammad
* Email:	admin@omar84.com
* Website:	http://omar84.com
* Component:Blank Component
* Version:	3.0.0
* Date:		03/11/2012
* copyright	Copyright (C) 2012 http://omar84.com. All Rights Reserved.
* @license	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

$app = JFactory::getApplication();
$admin = $app->isAdmin();

JToolBarHelper::title( JText::_( 'Wysyłanie wiadomości z kodami' ), 'groupedit');

if($admin==1)
	{
?>
<div>
	<?php
//echo getcwd();
$db = & JFactory::getDBO();
if(isset($_POST['akcja_usun']) && $_POST['akcja_usun']=='usun'){
for($h=0;$h<count($_POST['uzytkownicy']);$h++){
	
$querydodaj = "DELETE FROM #__wyslij_maila WHERE id='".$_POST['uzytkownicy'][$h]."'";
//echo $querydodaj; 
$db->setQuery( $querydodaj);
$db->query();
}
 header("Location: /administrator/index.php?option=com_dodajcomponent");
}


if(isset($_POST['akcja_dodaj']) && $_POST['akcja_dodaj']=='dodaj_poj'){

if(isset($_POST['email']) && $_POST['email']!=""){
$querydodaj = "INSERT INTO #__wyslij_maila (kto,kod) VALUES ('".$_POST['email']."','".$_POST['kod']."')";
$db->setQuery( $querydodaj);
$db->query();
}
 header("Location: /administrator/index.php?option=com_dodajcomponent");
}
require_once('class.phpmailer.php');
if(isset($_POST['akcja_wyslij']) && $_POST['akcja_wyslij']=='wyslij'){




$sql = "SELECT * FROM #__wyslij_maila";
$db->setQuery($sql);
$uzytkownicy_zapisani_2 = $db->loadObjectList();

foreach($uzytkownicy_zapisani_2 as $uzytkownik_zapisany){
	$_POST["tresc_wiadomosci"]=str_replace("images/","https://www.fitomento.com/images/",$_POST["tresc_wiadomosci"]);
	$tresc_emaila=str_replace("{KOD}",$uzytkownik_zapisany->kod,nl2br($_POST["tresc_wiadomosci"]));
	//echo $tresc_emaila; 
	

$mail9 = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
$mail9->IsSMTP(); // telling the class to use SMTP

try {
  $mail9->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
  $mail9->SMTPAuth   = true;                  // enable SMTP authentication
  $mail9->Host       = "msmtp3.iq.pl"; // sets the SMTP server
  $mail9->Port       = 587;                    // set the SMTP port for the GMAIL server
  $mail9->Username   = "fitomento.com_info_fitomento1"; // SMTP account username
  $mail9->Password   = "44wu5R0wMBQktoAs71Rh";        // SMTP account password

 
  $mail9->AddAddress($uzytkownik_zapisany->kto);
  $mail9->SetFrom('info@fitomento.com', 'Fitomento.com');
 // $mail9->AddReplyTo('lipinski8@gmail.com', 'First Last');
  $mail9->Subject  = $_POST['temat_wiadomosci'];
  $mail9->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail9->MsgHTML($tresc_emaila);
  $mail9->CharSet = "UTF-8";

  $mail9->Send();
  //echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}



header("Location: /administrator/index.php?option=com_dodajcomponent&komunikat=1");
}
if(isset($_GET['komunikat']) && $_GET['komunikat']=="1"){ echo "<span style='color:red;'>Wiadomości zostały poprawnie wysłane</span><br/><br/>"; }
if(isset($_POST['akcja']) && $_POST['akcja']=='wczytaj'){
	
$uploaddir = '/www/fitomento1_www/www/fitomento/administrator/pliki2/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);


if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    //echo "tak.\n";
} else {
   // echo "nie!\n";
}


$fileData=fopen('/www/fitomento1_www/www/fitomento/administrator/pliki2/'.$_FILES['userfile']['name'],'r');
$h=0;
while (($data2 = fgetcsv($fileData,0, ';')) !== FALSE) { $h++; if($h!=1 && $data2[0]!=""){
//echo $data2[0].'<br/>';
$querydodaj = "INSERT INTO #__wyslij_maila (kto,kod) VALUES ('".$data2[0]."','".$data2[1]."')";
$db->setQuery( $querydodaj);
$db->query();
}
}
 header("Location: /administrator/index.php?option=com_dodajcomponent");
 }
?>



<fieldset class="adminform hikashop_product_maininfo" id="htmlfieldset">
							
							<form  enctype="multipart/form-data" method="post" action="#">
							Wczytaj plik csv z e-mailami i kodami<br/>
							<input type="file" accept=".csv" name="userfile"/>
							<input type="hidden" value="wczytaj" name="akcja"/>
							<input type="submit" value="Wczytaj plik" />
							</form><hr/><br/>
							
							<form  enctype="multipart/form-data" method="post" action="#">
							Dodaj pojedynczego użytkownika <br/>
							<input type="text" placeholder="E-mail użytkownika" name="email"/>
							<input type="text" placeholder="Kod" name="kod"/>
							<input type="hidden" value="dodaj_poj" name="akcja_dodaj"/>
							<input type="submit" style="margin-top:-9px" value="Dodaj użytkownika" />
							</form><hr/><br/>
							
							
							<form method="post" action="#">
							<input type="hidden" name="akcja_wyslij" value="wyslij" />
							Temat wiadomości: <input type="text" name="temat_wiadomosci" /><br/><br/>
							Treść wiadomości wraz z {KOD}: 
							<?php 
							$editor =&JFactory::getEditor();
echo $editor->display('tresc_wiadomosci', '','100%','300px','','');
							?>
							<br/><br/><br/>
							<input class="btn  btn-success" style="display:block;clear:both;margin-top:30px;margin-left:0px; " type="submit" value="Wyślij wiadomości" onClick="return confirm('Czy na pewno wysłać wiadomość do wszystkich poniższych użytkowników?');" name="wyslij" />
							<br/>
							</form><hr/><br/>
							
							
							
							<form method="post" action="#">
							<input type="hidden" name="akcja_usun" value="usun" />
							<button style="margin-bottom:10px;" onClick="return confirm('Czy na pewno usunąć użytkownika / użytkowników?');" class="btn btn-small button-unpublish"><span class="icon-unpublish" aria-hidden="true"></span>Usuń użytkownika</button>
	
	
	
							
							<table class="table table-striped" width="clear:both; margin-top:10px;100%">
							<tr><td>Lp.</td><td><input id="zaznacz_odznacz" value="1" title="Zaznacz wszystkich użytkowników" onClick="zaznaczodznacz()" type="checkbox" /></td><td>Adres e-mail</td><td>Kod</td></tr>
							<?php 
							$sql = "SELECT * FROM #__wyslij_maila";
$db->setQuery($sql);
$uzytkownicy_zapisani = $db->loadObjectList();
$j=0;
foreach($uzytkownicy_zapisani as $uzytkownik_zapisany){ $j++;
							?>
							
							
							<tr><td><?php echo $j; ?></td>
							<td><input class="uzytkowni_zaznacz" type="checkbox" name="uzytkownicy[]" value="<?php echo $uzytkownik_zapisany->id; ?>" /></td>
							<td><?php echo $uzytkownik_zapisany->kto; ?></td>
							<td><?php echo $uzytkownik_zapisany->kod; ?></td>
							</tr>
							
<?php } ?>
							</table>
							</form>
							<script>
							function zaznaczodznacz(){
								
								var zaznacz_odznacz=document.getElementById('zaznacz_odznacz').checked;
								var tests = document.getElementsByClassName('uzytkowni_zaznacz');
								if(zaznacz_odznacz==true){
									for (i = 0; i < tests.length; i++) {
									document.getElementsByClassName('uzytkowni_zaznacz')[i].checked = true;
									} 
								}else{
									for (i = 0; i < tests.length; i++) {
									document.getElementsByClassName('uzytkowni_zaznacz')[i].checked = false;
									} 
								}
							}
							</script>
							<?php
								//$this->setLayout('normal');
								//echo $this->loadTemplate();
							?>
						</fieldset>
<br/><br/><br/>
<style>
.hikashop_custom_file_upload_link{max-height:200px}
#hikashop_product_ukadpodpisudlacertyfikatu input{margin-left:20px; margin-right:10px;}

</style>
</div>
<?php
	}
else
	{

	jimport('joomla.application.component.controller');

	// Create the controller
	$controller = JControllerLegacy::getInstance('DodajComponent');

	// Perform the Request task
	$controller->execute(JRequest::getCmd('task'));

	// Redirect if set by the controller
	$controller->redirect();
	}

 ?>