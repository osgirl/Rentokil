<?php
require_once '../../../php/PEAR/PHPUnit/Extensions/SeleniumTestCase.php';
 
class Customeradmin_test extends PHPUnit_Extensions_SeleniumTestCase
{
   /* protected function setUp()
    {
    	PHPUnit_Extensions_SeleniumTestCase::shareSession(true);
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://10.251.72.133/ri_frontend');
    }
 
    public function test_login()
    {
        $this->open('/ri_frontend/index.html');
        $this->type('txtUsername', 'cadmin');
        $this->assertEquals('cadmin', $this->getValue('txtUsername'));
        $this->type('txtPassword', 'cadmin');
        $this->assertEquals('cadmin', $this->getValue('txtPassword'));
       // $this->clickAt('id=btnLogin',20);
        $this->click('btnLogin');
        $this->waitForPageToLoad("30000");
       // $this->open('http://www.google.com');
       // $this->submit('frmLogin');
        //$this->keyPress('btnLogin','\13');
        //$this->fireEvent("id=btnLogin", "click");
       	//$this->waitForPageToLoad(10000);
        echo $this->getTitle();
        //$this->assertRegExp('/Jack Thompson/', $this->assertValue('//div', 'divWelcome'));
        //$this->assertStringEndsWith('ri_frontend/contractadmin.html', $this->getLocation());
    }*/
	
	protected function setUp()
	{
		PHPUnit_Extensions_SeleniumTestCase::shareSession(true);
		$this->setBrowser("*chrome");
		//$this->setBrowserUrl("http://10.251.72.133/ri_frontend");	// local
		$this->setBrowserUrl("https://riwebqa.cognizant.com/ri_frontend");	// QA link
	}

	public function test_login()
	{
		$this->open("/ri_frontend/index.html");
		$this->type("id=txtUsername", "cadmin");
		$this->type("id=txtPassword", "cadmin");
		$this->click("id=btnLogin");
		$this->waitForPageToLoad("30000");
		//$this->assertText('id=divWelcome', 'Welcome Jack Thompson');
		$this->test_session = $this->getText("//div[@id='divWelcome']");
		$this->assertRegExp('/steve cooper/', $this->getText("//div[@id='divWelcome']"));
	}
	
	public function test_create_contract()
    {
	    $this->click("id=btnAddContract");
	    $this->type("id=txtContractname", "test contract");
	    $this->click("id=btnNewContractSubmit");
	    $this->waitForPageToLoad("30000");
	    $this->assertText('id=ContractName', 'test contract');
    }
}
?>