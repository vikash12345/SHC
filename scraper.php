<?
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$MyWebsite = 'http://202.61.43.53/cfms-hc-search/cases/search-result?CasesSearch%5BCASENAMECODE%5D=&CasesSearch%5BCASENO%5D=&CasesSearch%5BCASEYEAR%5D=&CasesSearch%5BCIRCUITCODE%5D=&CasesSearch%5BMATTERCODE%5D=&CasesSearch%5BPARTY%5D=&CasesSearch%5BGOVT_AGENCY_CODE%5D=&CasesSearch%5BFIRNO%5D=&CasesSearch%5BFIRYEAR%5D=&CasesSearch%5BPOLICESTATIONCODE%5D=&CasesSearch%5BADVOCATECODE%5D=&CasesSearch%5BisPending%5D=3&page=';

$serialnumb = 0;
for ($page = 1; $page < 2; $page++) 
     {
        $NewURL = $MyWebsite . $page . '&per-page=15';
        $html = file_get_html($NewURL);
               
        foreach($html->find("//*[@id='w1-container']/table/tbody/tr") as $element)
		{
   if ($element)		
        {
			
           $number = $element->find("td", 0);
		   $Case  = $element->find("td", 1);
		   $CaseNO = $element->find("td", 2);
		   $CaseY = $element->find("td", 3);
		   $Bench = $element->find("td", 4);
		   $court = $element->find("td", 5);
		   $Casetitle = $element->find("td", 6);
		   $Matter	 = $element->find("td", 7);
		   $nextdate = $element->find("td", 8);
		   $linkraw = $element->find(".//td/a", 0);
			if (is_object($linkraw)) 
				{
			$link = $linkraw->href;
				} else 
					{
						$link = 'No link found!';
					}
		   

		   
           echo "$NewURL".'|'."$number" . '|'."$Case".'|'."$CaseNO".'|'."$CaseY".'|'."$Bench".'|'."$court".'|'."$Casetitle".'|'."$Matter".'|'."$nextdate".'|'."http://202.61.43.53$link"; 
		   
		 //  $CaseNO $CaseY $Bench $court $Casetitle $Matter $nextdate $link';
		   
           echo '<br/>';
        }
		
		}
 echo '-------------------------------------------<br/>';

    }     













$db = new PDO('sqlite:data.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
  $db->query('CREATE TABLE data(
    NewURL VARCHAR(100),
    description VARCHAR(100),
    number VARCHAR(100),
    Case VARCHAR(100),
    CaseNO VARCHAR(100),
    CaseY VARCHAR(100),
    Bench VARCHAR(100),
    court VARCHAR(100),
    Casetitle VARCHAR(100),
    Matter VARCHAR(100),
    nextdate VARCHAR(100),
    link VARCHAR(100),
    PRIMARY KEY (Case))');
    
   
   } catch (Exception $e) { 
	
}

?>
