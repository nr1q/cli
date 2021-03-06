<?php

use League\CLImate\CLImate;
use Symfony\Component\Finder\Finder;
use Tonik\CLI\CLI;
use Tonik\CLI\Command\Shake;
use Tonik\CLI\Renaming\Renamer;

class RenamingTest extends StubsCase
{
    public $answers = [
        '{{ theme.name }}' => 'Theme Name',
        '{{ theme.url }}' => 'Theme URI',
        '{{ theme.description }}' => 'Theme Description',
        '{{ theme.version }}' => 'Theme Version',
        '{{ theme.author }}' => 'Author',
        '{{ theme.author.url }}' => 'Author Website',
        '{{ theme.textdomain }}' => 'Theme Textdomain',
        'Tonik\Theme' => 'My\\\\New\\\\Theme',
    ];

    protected function setUp()
    {
        $this->setFixtures(dirname(__DIR__).'/fixtures');
        $this->setDestination("{$this->fixtures}/renaming");

        parent::setUp();
    }

    /**
     * @test
     */
    public function test_renaming_a_theme()
    {
        (new Renamer($this->destination))->replace($this->answers);

        $this->assertFileContains('My\New\Theme\Rest\Of\Name', "$this->destination/namespace.php");
        $this->assertFileContains('My\\\\New\\\\Theme\\\\Rest\\\\Of\\\\Name', "$this->destination/namespace.json");

        $this->assertFileContains("add_action('init', 'My\New\Theme\Rest\Of\Name');", "$this->destination/hooks.php");
        $this->assertFileContains("add_filter('excerpt', 'My\New\Theme\Rest\Of\Name');", "$this->destination/hooks.php");

        $this->assertFileContains("'textdomain' => 'Theme Textdomain'", "$this->destination/config.php");

        $this->assertFileContains('Theme Name: Theme Name', "$this->destination/style.css");
        $this->assertFileContains('Theme URI: Theme URI', "$this->destination/style.css");
        $this->assertFileContains('Description: Theme Description', "$this->destination/style.css");
        $this->assertFileContains('Version: Theme Version', "$this->destination/style.css");
        $this->assertFileContains('Author: Author', "$this->destination/style.css");
        $this->assertFileContains('Author URI: Author Website', "$this->destination/style.css");
        $this->assertFileContains('Text Domain: Theme Textdomain', "$this->destination/style.css");
    }
}
