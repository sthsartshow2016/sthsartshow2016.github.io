<?php
function createPiecePage( $connection , $id )
{
    $query = mysqli_query( $connection , "SELECT * FROM pieces WHERE id = $id ORDER BY data ASC" );
    $first = true;
    $name;
    $title;
    $type;
    $statement;
    $statementFull = "";
    $pieces = "";
    
    while( $piece = mysqli_fetch_array( $query ) )
    {
        $data = $piece[ "data" ];
        
        if( $first )
        {
            $first = false;
            $name = $piece[ "first" ] . " " . $piece[ "last" ];
            $title = $piece[ "title" ];
            $type = $piece[ "type" ];
            $statement = $piece[ "statement" ];
        }
        
        if( $type == 1 )
        {
            $pieces = $pieces . "<a href = '../pieces/$data' target = '_blank'><img class = 'mainPiece' src = '../pieces/$data'/></a>";
        }
        else if( $type == 2 )
        {
        	$pieces = $pieces . "<audio controls>
  				<source src = '../music/$data' type = 'audio/mpeg'>
				Your browser does not support music playback
			</audio>";
        }
        else if( $type == 3 )
        {
        	$videoEmbed = $piece[ "videoEmbed" ];
        	$pieces = $pieces . $videoEmbed;
        }
    }
    
    if( !empty( $statement ) )
    {
        $statementFull = "<h2>Artist's Statement</h2><p>$statement</p>";
    }
    
    $pageContent =  "<!DOCTYPE HTML>
            <head>
                <title>$name | STHS Art Show 2016</title>
                <meta charset = 'utf-8'/>
                <link rel = 'stylesheet' type = 'text/css' href = '../style.css'/>
                <link rel = 'icon' type = 'image/png' href = '../images/favicon.png'>
            </head>
        <body>
            <div id = 'header'>
                <div id = 'headerTitle'>
                    <a href = '../index.html'>STHS ART SHOW 2016</a>
                </div>
                <div id = 'headerBar'></div>
            </div>

            <div id = 'bodyWrapping'>
                <h1 style = 'margin-bottom: 0'>$name</h1>
                <h2 style = 'margin-top: 0'>$title</h2>
                <center>$pieces</center>
                $statementFull
            </div>
        </body>";
    
    $name = str_replace( " " , "_" , $name );
    $name = str_replace( "'" , "" , $name );
    $name = strtolower( $name );
    $fileName = $name . ".html";
    if( file_exists( "pages/" . $fileName ) )
    {
        $i = 0;
        while( true )
        {
            if( file_exists( "pages/" . $name . $i . ".html" ) )
            {
                $i++;
            }
            else
            {
                break;
            }
        }
        $fileName = $name . $i . ".html";
    }
    
    $newFile = fopen( "pages/" . $fileName , "w" );
    fwrite( $newFile, $pageContent );
    fclose( $newFile );
    
    mysqli_query( $connection , "UPDATE pieces SET url = '$fileName' WHERE id = $id" );
}
?>