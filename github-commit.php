<?php
/**
 *
 *
 */

if( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is part of an extension to the MediaWiki software and cannot be used standalone.\n" );
	die( 1 );
}

$wgExtensionCredits['other'][] = array(
        'name'=>'github.com commitLister',
        'author'=>'Nepomuk Fraedrich',
        'description'=>'lists defined count of commit messages of an given repo hosted on github.com',
        'version'=>'0.1'
);

if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
        $wgHooks['ParserFirstCallInit'][] = 'efGitHubCommit';
} else {
        $wgExtensionFunctions[] = 'efGitHubCommit';
}

function efGitHubCommit() {
        global $wgParser;
        $wgParser->setHook( 'githubcommit', 'efGitHubCommitRender' );
        return true;
}

function efGitHubCommitRender( $input, $args, $parser )
{
        $attr = array();
        $offset = 0;
        $count = 1;

        $user = 'nepda';
        $repo = 'mediawiki-github-commit';

        // This time, make a list of attributes and their values,
        // and dump them, along with the user input
		foreach( $args as $name => $value )
		{
			switch($name)
			{
				case 'offset':
					$offset = (int)$value;
					break;
				case 'count':
					$count = (int)$value;
					break;
				case 'user':
					$user = $value;
					break;
				case 'repo':
					$repo = $value;
					break;
			}
        }

		$cfg_api_url = 'https://api.github.com/repos/'.$user.'/'.$repo.'/commits';

		$json = file_get_contents($cfg_api_url);

		$data = json_decode($json);

        var_dump($data);
}