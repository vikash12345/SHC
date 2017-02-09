<?
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$MyWebsite = 'http://202.61.43.53/cfms-hc-search/cases/search-result?casesSearch%5BCASENAMECODE%5D=&casesSearch%5BCASENO%5D=&casesSearch%5BCASEYEAR%5D=&casesSearch%5BCIRCUITCODE%5D=&casesSearch%5BMATTERCODE%5D=&casesSearch%5BPARTY%5D=&casesSearch%5BGOVT_AGENCY_CODE%5D=&casesSearch%5BFIRNO%5D=&casesSearch%5BFIRYEAR%5D=&casesSearch%5BPOLICESTATIONCODE%5D=&casesSearch%5BADVOCATECODE%5D=&casesSearch%5BisPending%5D=3&page=';

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
		   $case  = $element->find("td", 1);
		   $caseNO = $element->find("td", 2);
		   $caseY = $element->find("td", 3);
		   $Bench = $element->find("td", 4);
		   $court = $element->find("td", 5);
		   $casetitle = $element->find("td", 6);
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
		   

		   
           echo "$NewURL".'|'."$number" . '|'."$case".'|'."$caseNO".'|'."$caseY".'|'."$Bench".'|'."$court".'|'."$casetitle".'|'."$Matter".'|'."$nextdate".'|'."http://202.61.43.53$link"; 
		   
		 //  $caseNO $caseY $Bench $court $casetitle $Matter $nextdate $link';
		   
           echo '<br/>';
        }
		
		}
 echo '-------------------------------------------<br/>';

    }     













$db = new PDO('sqlite:data.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
  $db->query('CREATE TABLE data(
    case VARCHAR(300),
    NewURL VARCHAR(300),
    description VARCHAR(300),
    number VARCHAR(300),
    caseNO VARCHAR(300),
    caseY VARCHAR(300),
    Bench VARCHAR(300),
    court VARCHAR(300),
    casetitle VARCHAR(300),
    Matter VARCHAR(300),
    nextdate VARCHAR(300),
    link VARCHAR(300),
    PRIMARY KEY (case))');
    
   
   } catch (Exception $e) { 
	
}
$articles = array(array('case' => '$case', 'NewURL' => '$NewURL', 'description' => '$description', 'number' => '$number','caseNO' => "$caseNO", 'caseY' => '$caseY', 'Bench' => '$Bench', 'court' => '$court', 'casetitle' => '$casetitle','Matter' => "$Matter", 'nextdate' => '$nextdate', 'link' => '$link'));
foreach ($articles as $article)
	{
$exists = $db->query("SELECT * FROM data WHERE case = " . $db->quote($article->case))->fetchObject();	
	
	if (!$exists) {
    $sql = "INSERT INTO data(case, NewURL, description, number,caseNO,caseY,Bench,court,casetitle,Matter,nextdate,link) VALUES(:case, :NewURL, :description, :number,:caseNO,:caseY,:Bench,:court,:casetitle,:Matter,:nextdate,:link)";
  } 
	 else {
    $sql = "UPDATE data SET description = :description, NewURL = :NewURL , number = :number , caseNO= :caseNO , caseY =:caseY  ,Bench=:Bench , court = :court , casetitle = :casetitle , Matter = :Matter , nextdate = :nextdate  , link = :link 
    
    WHERE case = :case";
  }
	
	
	
  $statement = $db->prepare($sql);
  $statement->execute(array(
    ':case' => $article['case'], 
    ':NewURL' => $article['NewURL'],
    ':description' => $article['description'],
    ':number' => $article['number'],
    ':caseNO' => $article['caseNO'], 
    ':caseY' => $article['caseY'],
    ':Bench' => $article['Bench'],
    ':court' => $article['court'],  
    ':casetitle' => $article['casetitle'], 
    ':nextdate' => $article['nextdate'],
    ':link' => $article['link']
	    
	    
  ));
}



?>
