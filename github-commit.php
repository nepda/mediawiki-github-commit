<?php
/**
 *
 *
 */

if(!defined('MEDIAWIKI'))
{
    echo( "This file is part of an extension to the MediaWiki software and cannot be used standalone.\n" );
    die( 1 );
}

$wgExtensionCredits['other'][] = array(
    'name'=>'github.com commitLister',
    'author'=>'Nepomuk Fraedrich',
    'description'=>'lists defined count of commit messages of an given repo hosted on github.com',
    'version'=>'0.1'
);

if (defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT')) 
{
    $wgHooks['ParserFirstCallInit'][] = 'efGitHubCommit';
} else {
    
    $wgExtensionFunctions[] = 'efGitHubCommit';
}

function efGitHubCommit()
{
    global $wgParser;
    $wgParser->setHook('githubcommit', 'efGitHubCommitRender');
    return true;
}


function efGitHubCommitRender($input, $args, $parser)
{
    $attr = array();
    $offset = 0;
    $count = 1;

    $user = 'nepda';
    $repo = 'mediawiki-github-commit';
    $date_format = "d.m.Y H:i";

    $str = "";

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
            case 'dateformat':
                $date_format = $value;
                break;
        }
    }


    $tpl_before = "<ul>";
    $tpl = "<li><code>%commit_committer_date%:</code> %commit_message% <small>by %commit_committer_name%</small> <a href=\"https://github.com/$user/$repo/tree/%commit_sha%\">Tree zeigen</a></li>";
    $tpl_after = "</ul>";
    

    $cfg_api_url = 'https://api.github.com/repos/'.$user.'/'.$repo.'/commits';

    $json = file_get_contents($cfg_api_url);

    $data = json_decode($json);


    $str .= $tpl_before;
    for($i = $offset; $i <= $count; $i++)
    {
        if (empty($data[$i]))
            continue;

        $commit = array(
            '%commit_url%' => $data[$i]->commit->url,
            '%commit_committer_email%' => $data[$i]->commit->committer->email,
            '%commit_committer_date%' => date($date_format, strtotime($data[$i]->commit->committer->date)),
            '%commit_committer_name%' => $data[$i]->commit->committer->name,
            '%commit_message%' => $data[$i]->commit->message,
            '%commit_tree_url%' => $data[$i]->commit->tree->url,
            '%commit_tree_sha%' => $data[$i]->commit->tree->sha,
            '%commit_sha%' => $data[$i]->commit->sha,
            '%commit_author_email%' => $data[$i]->commit->author->email,
            '%commit_author_date%' => date($date_format, strtotime($data[$i]->commit->author->date)),
            '%commit_author_name%' => $data[$i]->commit->author->name
        );

        $tmp_tpl = str_replace(array_keys($commit), array_values($commit), $tpl);

        $str .= $tmp_tpl;
    }
    $str .= $tpl_after;


    return $str;
}
