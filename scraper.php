<?
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$MyWebsite = 'http://202.61.43.53/cfms-hc-search/cases/search-result?CasesSearch%5BCASENAMECODE%5D=&CasesSearch%5BCASENO%5D=&CasesSearch%5BCASEYEAR%5D=&CasesSearch%5BCIRCUITCODE%5D=&CasesSearch%5BMATTERCODE%5D=&CasesSearch%5BPARTY%5D=&CasesSearch%5BGOVT_AGENCY_CODE%5D=&CasesSearch%5BFIRNO%5D=&CasesSearch%5BFIRYEAR%5D=&CasesSearch%5BPOLICESTATIONCODE%5D=&CasesSearch%5BADVOCATECODE%5D=&CasesSearch%5BisPending%5D=3&page=';

$serialnumb = 0;
for ($page = 2; $page < 3; $page++) 
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
// creating sqlite database 
try {
  $db->query('CREATE TABLE data(
   case VARCHAR(300),
    newURL VARCHAR(300),
    description VARCHAR(300),
    number VARCHAR(300),
    caseNO VARCHAR(300),
    caseY VARCHAR(300),
    bench VARCHAR(300),
    court VARCHAR(300),
    casetitle VARCHAR(300),
    matter VARCHAR(300),
    nextdate VARCHAR(300),
    link VARCHAR(300),
    PRIMARY KEY (Case))');
    
   
   } catch (Exception $e) { 
	
}






$articles = array(array('case' => $Case, 'newURL' => $NewURL, 'description' => $description, 'number' => $number,'CaseNO' => $caseNO, 'CaseY' => $caseY', 'Bench' => $bench, 'court' => $court, 'Casetitle' => $casetitle','Matter' => $matter, 'nextdate' => $nextdate, 'link' => $link));
foreach ($articles as $article)
	{
$exists = $db->query("SELECT * FROM data WHERE Case = " . $db->quote($article->Case))->fetchObject();	
	
	if (!$exists) {
    $sql = "INSERT INTO data(Case, NewURL, description, number,CaseNO,CaseY,Bench,court,Casetitle,Matter,nextdate,link) VALUES(:Case, :NewURL, :description, :number,:CaseNO,:CaseY,:Bench,:court,:Casetitle,:Matter,:nextdate,:link)";
  } 
	 else {
    $sql = "UPDATE data SET description = :description, NewURL = :NewURL , number = :number , CaseNO= :CaseNO , CaseY =:CaseY  ,Bench=:Bench , court = :court , Casetitle = :Casetitle , Matter = :Matter , nextdate = :nextdate  , link = :link 
    
    WHERE Case = :Case";
  }
		
  	$statement = $db->prepare($sql);
  	$statement->execute(array(
    	':Case' => $article['Case'], 
    	':NewURL' => $article['NewURL'],
    	':description' => $article['description'],
    	':number' => $article['number'],
    	':CaseNO' => $article['CaseNO'], 
    	':CaseY' => $article['CaseY'],
    	':Bench' => $article['Bench'],
    	':court' => $article['court'],  
    	':Casetitle' => $article['Casetitle'], 
    	':nextdate' => $article['nextdate'],
    	':link' => $article['link']
	    
	    
  ));
}



?>
