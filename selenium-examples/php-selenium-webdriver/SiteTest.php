<?php

namespace Facebook\WebDriver;

require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;

#https://github.com/php-webdriver/php-webdriver
#https://phpunit.readthedocs.io/pt_BR/latest/


#Toda classe tem que estender TestCase e termina com 'Test"
#O nome da classe CamelCaseTest tem que ser o mesmo nome do arquivo
class SiteTest extends TestCase {

	#Configurações iniciais para todos os testes
	protected function setUp(): void {
		$serverUrl = 'http://localhost:4444';
		// Chrome
		$this->driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
	}

	#Todo teste tem que começar com "test..."
    public function testRegistro(){
		// Abre o endereço
		$this->driver->get('https://www2.ifrn.edu.br/sirab/testes-selenium/public/register');

		$idTest = rand(0,1000);

		$nomeUsuario = "Teste automatico $idTest";

		// Preenche os campos
		$this->driver->findElement(WebDriverBy::id('name'))
			->sendKeys($nomeUsuario);

		$this->driver->findElement(WebDriverBy::id('email'))
			->sendKeys("teste$idTest@gmail.com");

		$this->driver->findElement(WebDriverBy::id('password'))
			->sendKeys('12345678');

		$this->driver->findElement(WebDriverBy::id('password-confirm'))
			->sendKeys('12345678')
			->submit();

		// procura o botao do menu superior
		$menuSuperior = $this->driver->findElement(
			WebDriverBy::cssSelector('#navbarDropdown')
		);

		// Exemplo de como pegar o texto do botao
		#print $menuSuperior->getText();

		// Confirma se o nome do usuário foi cadastrado corretamente
		$this->assertSame($nomeUsuario, $menuSuperior->getText());

		// Clica no menu superior
		$menuSuperior->click();

		// Make sure to always call quit() at the end to terminate the browser session
		$this->driver->quit();
	}


	public function testRegistroFail(){
		// Abre o endereço
		$this->driver->get('https://www2.ifrn.edu.br/sirab/testes-selenium/public/register');


		// Nao preenche nenhum campo, apenas submete
		$this->driver->findElement(WebDriverBy::tagName('form'))->submit();

		$el = $this->driver->findElement(WebDriverBy::xpath("//*[ text() = 'The name field is required.' ]"));
		$this->assertNotNull($el);

		$el = $this->driver->findElement(WebDriverBy::xpath("//*[ text() = 'The email field is required.' ]"));
		$this->assertNotNull($el);

		$el = $this->driver->findElement(WebDriverBy::xpath("//*[ text() = 'The password field is required.' ]"));
		$this->assertNotNull($el);

		// Make sure to always call quit() at the end to terminate the browser session
		$this->driver->quit();
	}
}


#testarRegistro($driver);